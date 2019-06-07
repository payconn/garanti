<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ModelInterface;
use Payconn\Common\ResponseInterface;
use Payconn\Common\TokenInterface;
use Payconn\Garanti\Model\Authorize;
use Payconn\Garanti\Response\AuthorizeResponse;
use Payconn\Garanti\Token;

class AuthorizeRequest extends GarantiRequest
{
    /**
     * @return ModelInterface|Authorize
     */
    public function getModel(): ModelInterface
    {
        return parent::getModel();
    }

    /**
     * @return TokenInterface|Token
     */
    public function getToken(): TokenInterface
    {
        return parent::getToken();
    }

    public function send(): ResponseInterface
    {
        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $this->getModel()->getBaseUrl(), [
            'form_params' => [
                'secure3dsecuritylevel' => '3d',
                'apiversion' => 'v0.01',
                'terminalprovuserid' => $this->getModel()->getUserId(),
                'txntype' => $this->getModel()->getType(),
                'cardnumber' => $this->getModel()->getCreditCard()->getNumber(),
                'cardexpiredatemonth' => $this->getModel()->getCreditCard()->getExpireMonth(),
                'cardexpiredateyear' => $this->getModel()->getCreditCard()->getExpireYear(),
                'cardcvv2' => $this->getModel()->getCreditCard()->getCvv(),
                'mode' => $this->getMode(),
                'terminalid' => $this->getToken()->getTerminalId(),
                'terminaluserid' => $this->getModel()->getUserId(),
                'terminalmerchantid' => $this->getToken()->getMerchantId(),
                'txnamount' => $this->getAmount(),
                'txncurrencycode' => $this->getModel()->getCurrency(),
                'txninstallmentcount' => $this->getModel()->getInstallment(),
                'orderid' => $this->getModel()->getOrderId(),
                'successurl' => $this->getModel()->getSuccessfulUrl(),
                'errorurl' => $this->getModel()->getFailureUrl(),
                'customeremailaddress',
                'customeripaddress' => $this->getIpAddress(),
                'secure3dhash' => $this->getHashData(),
            ],
        ]);

        return new AuthorizeResponse($this->getModel(), [
            'content' => $response->getBody()->getContents(),
        ]);
    }

    private function getHashData(): string
    {
        $securityData = mb_strtoupper(sha1(
            $this->getToken()->getPassword().
            '0'.$this->getToken()->getTerminalId()
        ));

        return mb_strtoupper(sha1(
            $this->getToken()->getTerminalId().
            $this->getModel()->getOrderId().
            $this->getAmount().
            $this->getModel()->getSuccessfulUrl().
            $this->getModel()->getFailureUrl().
            $this->getModel()->getType().
            $this->getModel()->getInstallment().
            $this->getToken()->getStoreKey().
            $securityData
        ));
    }
}
