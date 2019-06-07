<?php

namespace Payconn\Garanti\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Payconn\Common\HttpClient;
use Payconn\Garanti;
use Payconn\Garanti\Model\Complete;
use Payconn\Garanti\Token;
use PHPUnit\Framework\TestCase;

class CompleteTest extends TestCase
{
    public function testFailure()
    {
        $response = new Response(200, [], '<?xml version="1.0" encoding="UTF-8"?>
            <Root>
                <Transaction>
                    <Response>
                        <ReasonCode>99</ReasonCode>
                    </Response>
                </Transaction>
            </Root>');
        $mock = new MockHandler([
            $response,
        ]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        // complete
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
        $response = (new Garanti($token, $client))->complete($complete);
        $this->assertFalse($response->isSuccessful());
    }

    public function testSuccessful()
    {
        $response = new Response(200, [], '<?xml version="1.0" encoding="UTF-8"?>
            <Root>
                <Transaction>
                    <Response>
                        <ReasonCode>00</ReasonCode>
                    </Response>
                </Transaction>
            </Root>');
        $mock = new MockHandler([
            $response,
        ]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        // complete
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
        $response = (new Garanti($token, $client))->complete($complete);
        $this->assertTrue($response->isSuccessful());
    }
}
