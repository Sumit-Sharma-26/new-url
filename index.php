<?php
require 'vendor/autoload.php';
if (isset($_POST['authKey']) && ($_POST['authKey'] == "abc")) {
    $stripe = new \Stripe\StripeClient('sk_test_51O3D3jSFirmVAyBGc5YNzpOke4QJ2gKEZsVsk3nLvKABeAKQZmKriAK4QeEf2ZGNdOJgyaQisIzqOrJQId97ke8e00ui37velV');

// Use an existing Customer ID if this is a returning customer.
    $customer = $stripe->customers->create(
        [
            'name' => "Sumit",
            'address' => [
                'line1' => 'Demo address',
                'postal_code' => '371293',
                'city' => 'Pune',
                'state' => 'MH',
                'country' => 'India'
            ]
        ]
    );
    $ephemeralKey = $stripe->ephemeralKeys->create([
        'customer' => $customer->id,
    ], [
        'stripe_version' => '2022-08-01',
    ]);
    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => 10999,
        'currency' => 'inr',
        'description' => 'Payement for Android Course',
        'customer' => $customer->id,
        // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
        'automatic_payment_methods' => [
            'enabled' => 'true',
        ],
    ]);

    echo json_encode(
        [
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey' => $ephemeralKey->secret,
            'customer' => $customer->id,
            'publishableKey' => 'pk_test_51O3D3jSFirmVAyBGOoMWN0QuNhHqHRs8Ov7qlXVMREgTKTTbyKfjkfqIfqzhs20L6rUi4FoGUlYZ4FNU3glmAUlQ00dkBq8evQ'
        ]
    );
    http_response_code(200);
} else echo "Not authorized";