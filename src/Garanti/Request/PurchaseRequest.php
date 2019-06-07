<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Response\PurchaseResponse;

class PurchaseRequest extends GarantiRequest
{
    public function send(): ResponseInterface
    {
        $securityData = mb_strtoupper(sha1(
            $this->getToken()->getPassword().
            '0'.$this->getToken()->getTerminalId()
        ));
        $hashData = mb_strtoupper(sha1(
            $this->getModel()->getOrderId().
            $this->getToken()->getTerminalId().
            $this->getModel()->getCreditCard()->getNumber().
            $this->getAmount().
            $securityData
        ));

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-9"?><GVPSRequest></GVPSRequest>');
        $body->addChild('Mode', $this->getMode());
        $body->addChild('Version', 'v0.01');

        $terminal = $body->addChild('Terminal');
        $terminal->addChild('ProvUserID', $this->getModel()->getUserId());
        $terminal->addChild('HashData', $hashData);
        $terminal->addChild('UserID', $this->getModel()->getUserId());
        $terminal->addChild('ID', $this->getToken()->getTerminalId());
        $terminal->addChild('MerchantID', $this->getToken()->getMerchantId());

        $customer = $body->addChild('Customer');
        $customer->addChild('IPAddress', $this->getIpAddress());
        $customer->addChild('EmailAddress');

        $card = $body->addChild('Card');
        $card->addChild('Number', $this->getModel()->getCreditCard()->getNumber());
        $card->addChild('ExpireDate', $this->getModel()->getCreditCard()->getExpireMonth().$this->getModel()->getCreditCard()->getExpireYear());
        $card->addChild('CVV2', $this->getModel()->getCreditCard()->getCvv());

        $order = $body->addChild('Order');
        $order->addChild('OrderID', $this->getModel()->getOrderId());

        $transaction = $body->addChild('Transaction');
        $transaction->addChild('Type', $this->getModel()->getType());
        $transaction->addChild('InstallmentCnt', (string) $this->getModel()->getInstallment());
        $transaction->addChild('Amount', (string) $this->getAmount());
        $transaction->addChild('CurrencyCode', $this->getModel()->getCurrency());
        $transaction->addChild('CardholderPresentCode', '0');
        $transaction->addChild('MotoInd', 'N');

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $this->getModel()->getBaseUrl(), [
            'body' => $body->asXML(),
        ]);

        return new PurchaseResponse($this->getModel(), (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
