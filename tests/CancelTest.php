<?php

namespace Payconn\Garanti\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Payconn\Common\HttpClient;
use Payconn\Garanti\Model\Cancel;
use Payconn\Garanti\Token;
use PHPUnit\Framework\TestCase;

class CancelTest extends TestCase
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

        // cancel
        $token = new Token('123', '345', '12345');
        $cancel = new Cancel();
        $cancel->setTestMode(true);
        $cancel->setAmount(100);
        $cancel->setOrderId('GVP'.time());
        $cancel->setReturnedOrderId('ABC');
        $response = (new \Payconn\Garanti($token, $client))->cancel($cancel);
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

        // purchase
        $token = new Token('123', '345', '12345');
        $cancel = new Cancel();
        $cancel->setTestMode(true);
        $cancel->setAmount(100);
        $cancel->setOrderId('GVP'.time());
        $cancel->setReturnedOrderId('ABC');
        $response = (new \Payconn\Garanti($token, $client))->cancel($cancel);
        $this->assertTrue($response->isSuccessful());
    }
}
