<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Model\Complete;
use Payconn\Garanti\Response\CompleteResponse;
use Payconn\Garanti\Token;

class CompleteRequest extends AbstractRequest
{
    public function send(): ResponseInterface
    {
        /** @var Complete $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();
        $returnParams = $model->getReturnParams();

        // create hash
        $securityData = mb_strtoupper(sha1($token->getPassword().'0'.$returnParams->get('clientid')));
        $hashData = mb_strtoupper(sha1($returnParams->get('orderid').$returnParams->get('clientid').$returnParams->get('txnamount').$securityData));

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-9"?><GVPSRequest></GVPSRequest>');
        $body->addChild('Mode', $model->isTestMode() ? 'TEST' : 'PROD');
        $body->addChild('Version', 'v0.01');
        $terminal = $body->addChild('Terminal');
        $terminal->addChild('ProvUserID', 'PROVAUT');
        $terminal->addChild('HashData', $hashData);
        $terminal->addChild('UserID', $token->getUserId());
        $terminal->addChild('ID', $token->getTerminalId());
        $terminal->addChild('MerchantID', $token->getMerchantId());
        $customer = $body->addChild('Customer');
        $customer->addChild('IPAddress', $returnParams->get('customeripaddress'));
        $customer->addChild('EmailAddress');
        $card = $body->addChild('Card');
        $card->addChild('Number');
        $card->addChild('ExpireDate');
        $card->addChild('CVV2');
        $order = $body->addChild('Order');
        $order->addChild('OrderID', $returnParams->get('orderid'));
        $transaction = $body->addChild('Transaction');
        $transaction->addChild('Type', $returnParams->get('txntype'));
        $transaction->addChild('InstallmentCnt', $returnParams->get('txninstallmentcount'));
        $transaction->addChild('Amount', $returnParams->get('txnamount'));
        $transaction->addChild('CurrencyCode', $returnParams->get('txncurrencycode'));
        $transaction->addChild('CardholderPresentCode', '13');
        $transaction->addChild('MotoInd', 'N');
        $secure3d = $transaction->addChild('Secure3D');
        $secure3d->addChild('AuthenticationCode', $returnParams->get('cavv'));
        $secure3d->addChild('SecurityLevel', $returnParams->get('eci'));
        $secure3d->addChild('TxnID', $returnParams->get('xid'));
        $secure3d->addChild('Md', $returnParams->get('md'));

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl(), [
            'body' => $body->asXML(),
        ]);

        return new CompleteResponse($model, (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
