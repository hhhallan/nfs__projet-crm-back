<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPMailer\PHPMailer\PHPMailer;
use Exception;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    public function save(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //genere une facture en pdf
    public function generateFacture(Facture $facture)
    {
        //TODO: generate pdf 
    }

    //sendMail phpmailer
    public function sendMail($email, $name, $subject, $message, $pdf): void
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'sandbox.smtp.mailtrap.io';  //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = $_ENV['USERNAME_MAIL'];                 // SMTP username
            $mail->Password   = $_ENV['PASSWORD_MAIL'];                 // SMTP password
            $mail->Port       = 2525;                                    //TCP port to connect to; use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('' . $_ENV['EMAIL_FROM'] . '', 'Facture');
            $mail->addAddress('' . $email . '', '' . $name . '');     //Add a recipient

            // Attachments
            $mail->addStringAttachment($pdf, 'facture.pdf');

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->CharSet = 'UTF-8';

            $mail->Subject = '' . $subject . '';
            $mail->Body    = '' . $message . '';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();

            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }



    //    /**
    //     * @return Facture[] Returns an array of Facture objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Facture
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
