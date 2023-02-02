<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FactureService;
use Stripe;

#[Route('/api')]
class StripeController extends AbstractController
{
    
    #[Route('/stripe/create-payment/{id}', name: 'app_stripe_payment', methods: ['POST'])]
    public function createPayment(Request $request, $id, FactureService $factureService)
    {
        $invoice = $factureService->read($id);

        if (!$invoice) {
            return new JsonResponse([
                'error' => 'No such invoice.'
            ], 400);
        }

        if ($invoice->getStat() === 'PAYED') {
            return new JsonResponse([
                'error' => 'Invoice already paid.'
            ], 400);
        }

        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        $card = [
            "number" => $request->request->get('card_number'),
            "exp_month" => $request->request->get('exp_month'),
            "exp_year" => $request->request->get('exp_year'),
            "cvc" => $request->request->get('cvc'),
        ];

        $token = Stripe\Token::create(["card" => $card]);
        $stripeToken = $token['id'];

        $amount = $request->request->get('amount');
        $description = $request->request->get('description');

        if (empty($stripeToken)) {
            return new JsonResponse([
                'error' => 'No such token.'
            ], 400);
        }

        try {
            Stripe\Charge::create([
                "amount" => $amount,
                "currency" => "eur",
                "source" => $stripeToken,
                "description" => $description
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Payment failed.'
            ], 400);
        }

        $factureService->changeState($id, 'PAYED');

        //envoie du mail avec la facture en pdf en pièce jointe au client
        $factureService->sendMail($id);

        return new JsonResponse([
            'message' => 'Payment Successful!'
        ], 200);
    }
}
