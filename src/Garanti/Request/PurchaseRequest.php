<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Model\Purchase;
use Payconn\Garanti\Response\PurchaseResponse;
use Payconn\Garanti\Token;

class PurchaseRequest extends AbstractRequest
{
    public function send(): ResponseInterface
    {
        /** @var Purchase $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $model->getToken();

        // hash
        $securityData = mb_strtoupper(sha1($token->getPassword().'0'.$token->getTerminalId()));
        $hashData = mb_strtoupper(sha1($model->getOrderId().$token->getTerminalId().$model->getCreditCard()->getNumber().($model->getAmount() * 100).$securityData));

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-9"?><GVPSRequest></GVPSRequest>');
        $body->addChild('Mode', $model->getMode());
        $body->addChild('Version', 'v0.01');
        $terminal = $body->addChild('Terminal');
        $terminal->addChild('ProvUserID', 'PROVAUT');
        $terminal->addChild('HashData', $hashData);
        $terminal->addChild('UserID', $token->getUserId());
        $terminal->addChild('ID', $token->getTerminalId());
        $terminal->addChild('MerchantID', $token->getMerchantId());
        $customer = $body->addChild('Customer');
        $customer->addChild('IPAddress', '127.0.0.1');
        $customer->addChild('EmailAddress');
        $card = $body->addChild('Card');
        $card->addChild('Number', $model->getCreditCard()->getNumber());
        $card->addChild('ExpireDate', $model->getCreditCard()->getExpireMonth().$model->getCreditCard()->getExpireYear());
        $card->addChild('CVV2', $model->getCreditCard()->getCvv());
        $order = $body->addChild('Order');
        $order->addChild('OrderID', $model->getOrderId());
        $transaction = $body->addChild('Transaction');
        $transaction->addChild('Type', 'sales');
        $transaction->addChild('InstallmentCnt', $model->getInstallment());
        $transaction->addChild('Amount', $model->getAmount() * 100);
        $transaction->addChild('CurrencyCode', $model->getCurrency());
        $transaction->addChild('CardholderPresentCode', '0');
        $transaction->addChild('MotoInd', 'N');

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl(), [
            'body' => $body->asXML(),
        ]);

        return new PurchaseResponse($model, (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
