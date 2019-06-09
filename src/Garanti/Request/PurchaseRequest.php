<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Model\Purchase;
use Payconn\Garanti\Response\PurchaseResponse;
use Payconn\Garanti\Token;

class PurchaseRequest extends GarantiRequest
{
    public function send(): ResponseInterface
    {
        /** @var Token $token */
        $token = $this->getToken();
        /** @var Purchase $model */
        $model = $this->getModel();
        $amount = strval($model->getAmount() * 100);

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-9"?><GVPSRequest></GVPSRequest>');
        $body->addChild('Mode', $this->getMode());
        $body->addChild('Version', 'v0.01');

        $terminal = $body->addChild('Terminal');
        $terminal->addChild('ProvUserID', $model->getUserId());
        $terminal->addChild('UserID', $model->getUserId());
        $terminal->addChild('ID', $token->getTerminalId());
        $terminal->addChild('MerchantID', $token->getMerchantId());
        $terminal->addChild('HashData', mb_strtoupper(sha1(
            $model->getOrderId().
            $token->getTerminalId().
            $model->getCreditCard()->getNumber().
            $amount.
            mb_strtoupper(sha1(
                $token->getPassword().
                '0'.$token->getTerminalId()
            ))
        )));

        $customer = $body->addChild('Customer');
        $customer->addChild('IPAddress', $this->getIpAddress());
        $customer->addChild('EmailAddress');

        $card = $body->addChild('Card');
        $card->addChild('Number', $model->getCreditCard()->getNumber());
        $card->addChild('ExpireDate', $model->getCreditCard()->getExpireMonth()->format('m').$model->getCreditCard()->getExpireYear()->format('y'));
        $card->addChild('CVV2', $model->getCreditCard()->getCvv());

        $order = $body->addChild('Order');
        $order->addChild('OrderID', $model->getOrderId());

        $transaction = $body->addChild('Transaction');
        $transaction->addChild('Type', $model->getType());
        $transaction->addChild('InstallmentCnt', (string) $model->getInstallment());
        $transaction->addChild('Amount', $amount);
        $transaction->addChild('CurrencyCode', $model->getCurrency());
        $transaction->addChild('CardholderPresentCode', '0');
        $transaction->addChild('MotoInd', 'N');

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl(), [
            'body' => $body->asXML(),
        ]);

        return new PurchaseResponse($this->getModel(), (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
