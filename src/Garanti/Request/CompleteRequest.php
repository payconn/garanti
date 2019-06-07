<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Model\Complete;
use Payconn\Garanti\Response\CompleteResponse;
use Payconn\Garanti\Token;

class CompleteRequest extends GarantiRequest
{
    public function send(): ResponseInterface
    {
        /** @var Complete $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-9"?><GVPSRequest></GVPSRequest>');
        $body->addChild('Mode', $this->getMode());
        $body->addChild('Version', 'v0.01');

        $terminal = $body->addChild('Terminal');
        $terminal->addChild('ProvUserID', $model->getUserId());
        $terminal->addChild('UserID', $model->getUserId());
        $terminal->addChild('ID', $token->getTerminalId());
        $terminal->addChild('MerchantID', $token->getMerchantId());
        $terminal->addChild('HashData', mb_strtoupper(sha1(
            $model->getReturnParams()->get('orderid').
            $model->getReturnParams()->get('clientid').
            $model->getReturnParams()->get('txnamount').
            mb_strtoupper(sha1(
                $token->getPassword().
                '0'.$model->getReturnParams()->get('clientid')
            ))
        )));

        $customer = $body->addChild('Customer');
        $customer->addChild('IPAddress', $model->getReturnParams()->get('customeripaddress'));
        $customer->addChild('EmailAddress');

        $card = $body->addChild('Card');
        $card->addChild('Number');
        $card->addChild('ExpireDate');
        $card->addChild('CVV2');

        $order = $body->addChild('Order');
        $order->addChild('OrderID', $model->getReturnParams()->get('orderid'));

        $transaction = $body->addChild('Transaction');
        $transaction->addChild('Type', $model->getReturnParams()->get('txntype'));
        $transaction->addChild('InstallmentCnt', $model->getReturnParams()->get('txninstallmentcount'));
        $transaction->addChild('Amount', $model->getReturnParams()->get('txnamount'));
        $transaction->addChild('CurrencyCode', $model->getReturnParams()->get('txncurrencycode'));
        $transaction->addChild('CardholderPresentCode', '13');
        $transaction->addChild('MotoInd', 'N');

        $secure3d = $transaction->addChild('Secure3D');
        $secure3d->addChild('AuthenticationCode', $model->getReturnParams()->get('cavv'));
        $secure3d->addChild('SecurityLevel', $model->getReturnParams()->get('eci'));
        $secure3d->addChild('TxnID', $model->getReturnParams()->get('xid'));
        $secure3d->addChild('Md', $model->getReturnParams()->get('md'));

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl(), [
            'body' => $body->asXML(),
        ]);

        return new CompleteResponse($this->getModel(), (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
