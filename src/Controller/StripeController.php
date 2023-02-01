<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;

#[Route('/api')]
class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }


    #[Route('/stripe/create-token', name: 'app_stripe_token', methods: ['POST'])]
    public function createToken(Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        $token = Stripe\Token::create([
            "card" => [
                "number" => $request->request->get('card_number'),
                "exp_month" => $request->request->get('exp_month'),
                "exp_year" => $request->request->get('exp_year'),
                "cvc" => $request->request->get('cvc'),
            ],
        ]);

        return new JsonResponse(['token' => $token['id']]);
    }


    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request)
    {
        $stripeToken = $request->request->get('stripeToken');
        $amount = $request->request->get('amount');
        $description = $request->request->get('description');

        if (empty($stripeToken)) {
            return new JsonResponse([
                'error' => 'No such token.'
            ], 400);
        }

        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create([
            "amount" => $amount,
            "currency" => "eur",
            "source" => $stripeToken,
            "description" => $description
        ]);

        return new JsonResponse([
            'message' => 'Payment Successful!'
        ], 200);
    }
}
