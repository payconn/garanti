<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Model\Authorize;
use Payconn\Garanti\Response\AuthorizeResponse;
use Payconn\Garanti\Token;

class AuthorizeRequest extends AbstractRequest
{
    public function send(): ResponseInterface
    {
        /** @var Authorize $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();

        // hash
        $securityData = mb_strtoupper(sha1($token->getPassword().'0'.$token->getTerminalId()));
        $hashData = mb_strtoupper(sha1($token->getTerminalId().$model->getOrderId().($model->getAmount() * 100).$model->getSuccessfulUrl().$model->getFailureUrl().'sales'.$model->getInstallment().$token->getStoreKey().$securityData));

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $this->getModel()->getBaseUrl(), [
            'form_params' => [
                'secure3dsecuritylevel' => '3d',
                'cardnumber' => $model->getCreditCard()->getNumber(),
                'cardexpiredatemonth' => $model->getCreditCard()->getExpireMonth(),
                'cardexpiredateyear' => $model->getCreditCard()->getExpireYear(),
                'cardcvv2' => $model->getCreditCard()->getCvv(),
                'mode' => $model->isTestMode() ? 'TEST' : 'PROD',
                'apiversion' => 'v0.01',
                'terminalprovuserid' => 'PROVAUT',
                'terminalid' => $token->getTerminalId(),
                'terminaluserid' => $token->getUserId(),
                'terminalmerchantid' => $token->getMerchantId(),
                'txntype' => 'sales',
                'txnamount' => $model->getAmount() * 100,
                'txncurrencycode' => $model->getCurrency(),
                'txninstallmentcount' => $model->getInstallment(),
                'orderid' => $model->getOrderId(),
                'successurl' => $model->getSuccessfulUrl(),
                'errorurl' => $model->getFailureUrl(),
                'customeremailaddress',
                'customeripaddress' => $this->getIpAddress(),
                'secure3dhash' => $hashData,
            ],
        ]);

        return new AuthorizeResponse($model, [
            'content' => $response->getBody()->getContents(),
        ]);
    }
}
