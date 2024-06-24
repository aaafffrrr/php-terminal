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

if (!isset($data['amount']) || !isset($data['name']) || !isset($data['email']) || !isset($data['address']) || !isset($data['city']) || !isset($data['state']) || !isset($data['zip'])) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

try {
    $paymentIntent = PaymentIntent::create([
        'amount' => $data['amount'] * 100, // amount in cents
        'currency' => 'usd',
        'payment_method_types' => ['card'],
        'description' => 'Payment from ' . $data['name'],
        'metadata' => [
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'zip' => $data['zip']
        ]
    ]);

    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret,
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
