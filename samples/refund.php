<?php

require_once __DIR__.'/../vendor/autoload.php';

use Payconn\Garanti;
use Payconn\Garanti\Currency;
use Payconn\Garanti\Model\Refund;
use Payconn\Garanti\Token;

$token = new Token('30691297', '7000679', 'PROVAUT', '123qweASD/');
$gateway = new Garanti($token);
$refund = (new Refund($token))
    ->setCurrency(Currency::TRY)
    ->setTestMode(true)
    ->setOrderId('payconn1559248587');
$response = $gateway->refund($refund);
print_r([
    'isSuccessful' => (int) $response->isSuccessful(),
    'message' => $response->getResponseMessage(),
    'code' => $response->getResponseCode(),
]);
