<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Model\Cancel;
use Payconn\Garanti\Response\CancelResponse;
use Payconn\Garanti\Token;

class CancelRequest extends AbstractRequest
{
    public function send(): ResponseInterface
    {
        /** @var Cancel $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();

        // hash
        $securityData = mb_strtoupper(sha1($token->getPassword().'0'.$token->getTerminalId()));
        $hashData = mb_strtoupper(sha1($model->getOrderId().$token->getTerminalId().($model->getAmount() * 100).$securityData));

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-9"?><GVPSRequest></GVPSRequest>');
        $body->addChild('Mode', $model->isTestMode() ? 'TEST' : 'PROD');
        $body->addChild('Version', 'v0.01');
        $terminal = $body->addChild('Terminal');
        $terminal->addChild('ProvUserID', 'PROVRFN');
        $terminal->addChild('HashData', $hashData);
        $terminal->addChild('UserID', $token->getUserId());
        $terminal->addChild('ID', $token->getTerminalId());
        $terminal->addChild('MerchantID', $token->getMerchantId());
        $customer = $body->addChild('Customer');
        $customer->addChild('IPAddress', '127.0.0.1');
        $customer->addChild('EmailAddress');
        $order = $body->addChild('Order');
        $order->addChild('OrderID', $model->getOrderId());
        $transaction = $body->addChild('Transaction');
        $transaction->addChild('OriginalRetrefNum', $model->getReturnedOrderId());
        $transaction->addChild('Type', 'void');
        $transaction->addChild('Amount', (string) ($model->getAmount() * 100));
        $transaction->addChild('InstallmentCnt');
        $transaction->addChild('CurrencyCode');
        $transaction->addChild('CardholderPresentCode', '0');
        $transaction->addChild('MotoInd', 'N');

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl(), [
            'body' => $body->asXML(),
        ]);

        return new CancelResponse($model, (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
