<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Response\CompleteResponse;

class CompleteRequest extends GarantiRequest
{
    public function send(): ResponseInterface
    {
        $securityData = mb_strtoupper(sha1(
            $this->getToken()->getPassword().
            '0'.$this->getModel()->getReturnParams()->get('clientid')
        ));

        $hashData = mb_strtoupper(sha1(
            $this->getModel()->getReturnParams()->get('orderid').
            $this->getModel()->getReturnParams()->get('clientid').
            $this->getModel()->getReturnParams()->get('txnamount').
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
        $customer->addChild('IPAddress', $this->getModel()->getReturnParams()->get('customeripaddress'));
        $customer->addChild('EmailAddress');

        $card = $body->addChild('Card');
        $card->addChild('Number');
        $card->addChild('ExpireDate');
        $card->addChild('CVV2');

        $order = $body->addChild('Order');
        $order->addChild('OrderID', $this->getModel()->getReturnParams()->get('orderid'));

        $transaction = $body->addChild('Transaction');
        $transaction->addChild('Type', $this->getModel()->getReturnParams()->get('txntype'));
        $transaction->addChild('InstallmentCnt', $this->getModel()->getReturnParams()->get('txninstallmentcount'));
        $transaction->addChild('Amount', $this->getModel()->getReturnParams()->get('txnamount'));
        $transaction->addChild('CurrencyCode', $this->getModel()->getReturnParams()->get('txncurrencycode'));
        $transaction->addChild('CardholderPresentCode', '13');
        $transaction->addChild('MotoInd', 'N');

        $secure3d = $transaction->addChild('Secure3D');
        $secure3d->addChild('AuthenticationCode', $this->getModel()->getReturnParams()->get('cavv'));
        $secure3d->addChild('SecurityLevel', $this->getModel()->getReturnParams()->get('eci'));
        $secure3d->addChild('TxnID', $this->getModel()->getReturnParams()->get('xid'));
        $secure3d->addChild('Md', $this->getModel()->getReturnParams()->get('md'));

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $this->getModel()->getBaseUrl(), [
            'body' => $body->asXML(),
        ]);

        return new CompleteResponse($this->getModel(), (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
