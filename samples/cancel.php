<?php

require_once __DIR__.'/../vendor/autoload.php';

use Payconn\Garanti;
use Payconn\Garanti\Currency;
use Payconn\Garanti\Model\Cancel;
use Payconn\Garanti\Token;

$token = new Token('30691297', '7000679', 'PROVAUT', '123qweASD/');
$cancel = new Cancel();
$cancel->setTestMode(true);
$cancel->setAmount(100);
$cancel->setCurrency(Currency::TRY);
$cancel->setOrderId('915305668794');
$response = (new Garanti($token))->cancel($cancel);
print_r([
    'isSuccessful' => (int) $response->isSuccessful(),
    'message' => $response->getResponseMessage(),
    'code' => $response->getResponseCode(),
]);
