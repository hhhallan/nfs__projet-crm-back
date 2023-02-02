<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Util;
use DateTimeImmutable;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PHPMailer\PHPMailer\PHPMailer;


class AuthService
{
    private readonly TokenStorageInterface $tokenStorage;
    private readonly UserRepository $userRepository;
    private readonly UserPasswordHasherInterface $hasher;
    public function __construct(UserRepository $userRepository, TokenStorageInterface $storage, UserPasswordHasherInterface $hasher)
    {
        $this->tokenStorage = $storage;
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    public function getUser(): User
    {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof TokenInterface) {
            /** @var User $user */
            $user = $token->getUser();
            return $user;
        } else throw new Exception("no user found");
    }

    public function resetPassword(?string $email, bool $exist = true): bool
    {
        $user = $this->userRepository->findOneBy(['email' => $email, 'validate' => $exist]);
        if ($user != null) {
            $token = Util::generateToken();
            $user->setResetToken($token)
                ->setResetExpire(new DateTimeImmutable('+1 hour'));

            $this->userRepository->save($user, true);

            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'sandbox.smtp.mailtrap.io';                       // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = $_ENV['USERNAME_MAIL'];                 // SMTP username
                $mail->Password   = $_ENV['PASSWORD_MAIL'];                 // SMTP password
                $mail->Port       = 2525;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom('your_email@example.com', 'Mailer');
                $mail->addAddress("maxkb02@gmail.com", 'Joe User');                      // Add a recipient

                // Content
                $mail->CharSet = 'UTF-8';
                $mail->isHTML(true);

                //si user validate = false alors on envoie un mail pour valider le compte sinon on envoie un mail pour réinitialiser le mot de passe
                if ($user->isValidate() == false) {
                    $mail->Subject = 'Validation du compte';
                    $mail->Body    = '<head>
                    <meta charset="UTF-8">
                    <title>Récupération de mot de passe</title>
                    <style>
                      /* Style pour le corps du message */
                      body {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        padding: 40px;
                      }
                
                      /* Style pour la boîte de contenu */
                      .content-box {
                        background-color: #fff;
                        border: 1px solid #ccc;
                        border-radius: 10px;
                        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
                        width: 60%;
                        margin: 0 auto;
                        padding: 20px;
                      }
                
                      /* Style pour le titre */
                      h1 {
                        text-align: center;
                        color: #333;
                      }
                
                      /* Style pour les boutons */
                      .btn {
                        background-color: #4CAF50;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        margin: 20px auto;
                        border-radius: 5px;
                        font-size: 16px;
                      }
                    </style>
                  </head><body>
                    <div class="content-box">
                      <h1>Validation de votre compte</h1>
                      <p>Bonjour,</p>
                      <p>Vous avez demandé la validation de votre compte. Pour valider votre compte, veuillez cliquer sur le bouton ci-dessous :</p>
                      <a href="#" class="btn">valider votre compte</a>
                      <p>Si vous n\'êtes pas à l\'origine de cette demande, veuillez ignorer cet email.</p>
                      <p>Merci,</p>
                      <p>L\'équipe de support</p>
                    </div>
                  </body>';
                    $mail->AltBody = 'Cliquez sur ce lien pour activer votre compte: ' . $token;
                } else {
                    $mail->Subject = 'Réinitialisation du mot de passe';
                    $mail->Body    = '<head>
                    <meta charset="UTF-8">
                    <title>Récupération de mot de passe</title>
                    <style>
                      /* Style pour le corps du message */
                      body {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        padding: 40px;
                      }
                
                      /* Style pour la boîte de contenu */
                      .content-box {
                        background-color: #fff;
                        border: 1px solid #ccc;
                        border-radius: 10px;
                        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
                        width: 60%;
                        margin: 0 auto;
                        padding: 20px;
                      }
                
                      /* Style pour le titre */
                      h1 {
                        text-align: center;
                        color: #333;
                      }
                
                      /* Style pour les boutons */
                      .btn {
                        background-color: #4CAF50;
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        margin: 20px auto;
                        border-radius: 5px;
                        font-size: 16px;
                      }
                    </style>
                  </head><body>
                    <div class="content-box">
                      <h1>Récupération de mot de passe</h1>
                      <p>Bonjour,</p>
                      <p>Vous avez demandé la récupération de votre mot de passe. Pour créer un nouveau mot de passe, veuillez cliquer sur le bouton ci-dessous :</p>
                      <a href="#" class="btn">Créer un nouveau mot de passe</a>
                      <p>Si vous n\'êtes pas à l\'origine de cette demande, veuillez ignorer cet email.</p>
                      <p>Merci,</p>
                      <p>L\'équipe de support</p>
                    </div>
                  </body>';
                    $mail->AltBody = 'Cliquez sur ce lien pour réinitialiser votre mot de passe: ' . $token;
                }

                $mail->send();
                return true;
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                return false;
            }
        }
        return false;
    }


    public function changePassword(string $token, string $password): bool
    {
        $user = $this->userRepository->findOneBy(['resetToken' => $token]);
        if ($user != null) {
            $user->setPassword($this->hasher->hashPassword($user, $password))
                ->setResetToken(null)
                ->setResetExpire(null);

            $this->userRepository->save($user, true);
            return true;
        }
        return false;
    }
}
