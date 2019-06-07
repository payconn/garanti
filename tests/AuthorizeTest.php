<?php

namespace Payconn\Garanti\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Payconn\Common\CreditCard;
use Payconn\Common\HttpClient;
use Payconn\Garanti\Currency;
use Payconn\Garanti\Model\Authorize;
use Payconn\Garanti\Token;
use PHPUnit\Framework\TestCase;

class AuthorizeTest extends TestCase
{
    public function testSuccessful()
    {
        $response = new Response(200, [], 'TEST_CONTENT');
        $mock = new MockHandler([
            $response,
        ]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        // purchase
        $token = new Token('123', '345', '12345');
        $creditCard = new CreditCard('4355084355084358', '26', '12', '000');
        $authorize = new Authorize();
        $authorize->setTestMode(true);
        $authorize->setCreditCard($creditCard);
        $authorize->setCurrency(Currency::TRY);
        $authorize->setAmount(100);
        $authorize->setInstallment(1);
        $authorize->setOrderId('GVP'.time());
        $authorize->setSuccessfulUrl('http://127.0.0.1:8000/successful');
        $authorize->setFailureUrl('http://127.0.0.1:8000/failure');
        $response = (new \Payconn\Garanti($token, $client))->authorize($authorize);
        $this->assertEquals('TEST_CONTENT', $response->getRedirectForm());
    }
}
