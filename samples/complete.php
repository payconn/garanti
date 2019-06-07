<?php

require_once __DIR__.'/../vendor/autoload.php';

use Payconn\Garanti;
use Payconn\Garanti\Model\Complete;
use Payconn\Garanti\Response\CompleteResponse;
use Payconn\Garanti\Token;

$token = new Token('30691297', '7000679', '123qweASD/');
$complete = new Complete();
$complete->setTestMode(true);
$complete->setReturnParams([
    'clientid' => '30691297',
    'txnamount' => '10000',
    'terminalprovuserid' => 'PROVAUT',
    'terminaluserid' => 'PROVAUT',
    'customeripaddress' => '127.0.0.1',
    'orderid' => 'GVP1559853532',
    'txntype' => 'sales',
    'txninstallmentcount' => '1',
    'txncurrencycode' => '949',
    'cavv' => 'jCm0m+u/0hUfAREHBAMBcfN+pSo=',
    'eci' => '02',
    'xid' => 'RszfrwEYe/8xb7rnrPuh6C9pZSQ=',
    'md' => 'G1YfkxEZ8Noemg4MRspO20vEiXaEk51AfTkzBYXctfXYyWOFtgVi3KWCgNHWER4xIgXy22Y9tRowI4onKEtKH13ojSZbgXanK/rtyFwJ/+AqopYA/HZpOCJwzWN8vubyFW+8SVj5QCU7XIga231wGegIWnqx8bZ5',
]);
/** @var CompleteResponse $response */
$response = (new Garanti($token))->complete($complete);
print_r([
    'isSuccessful' => (int) $response->isSuccessful(),
    'message' => $response->getResponseMessage(),
    'code' => $response->getResponseCode(),
    'orderId' => $response->getOrderId(),
]);
