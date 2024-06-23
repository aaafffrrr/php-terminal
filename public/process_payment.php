<?php

require '../vendor/autoload.php';

use Stripe\Stripe;
use Stripe\PaymentIntent;

header('Content-Type: application/json');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
Stripe::setApiKey($stripeSecretKey);

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['amount'])) {
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

try {
    $paymentIntent = PaymentIntent::create([
        'amount' => $data['amount'] * 100, // amount in cents
        'currency' => 'usd',
        'payment_method_types' => ['card_present'],
    ]);

    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret,
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
