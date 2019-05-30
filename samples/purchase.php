<?php

require_once __DIR__.'/../vendor/autoload.php';

use Payconn\Common\CreditCard;
use Payconn\Garanti;
use Payconn\Garanti\Currency;
use Payconn\Garanti\Model\Purchase;
use Payconn\Garanti\Token;

$token = new Token('30691297', '7000679', 'PROVAUT', '123qweASD/');
$gateway = new Garanti($token);
$creditCard = new CreditCard('4282209027132016', '20', '05', '165');
$purchase = (new Purchase($token))
    ->setCreditCard($creditCard)
    ->setCurrency(Currency::TRY)
    ->setTestMode(true)
    ->setAmount(1)
    ->setInstallment(1)
    ->setOrderId('JET006'.time());
$response = $gateway->purchase($purchase);
print_r([
    'isSuccessful' => (int) $response->isSuccessful(),
    'message' => $response->getResponseMessage(),
    'code' => $response->getResponseCode(),
    'orderId' => $response->getOrderId(),
]);
