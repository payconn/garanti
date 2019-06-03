<?php

namespace Payconn;

use Payconn\Common\AbstractGateway;
use Payconn\Common\BaseUrl;
use Payconn\Common\Model\AuthorizeInterface;
use Payconn\Common\Model\CancelInterface;
use Payconn\Common\Model\CompleteInterface;
use Payconn\Common\Model\PurchaseInterface;
use Payconn\Common\Model\RefundInterface;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Request\AuthorizeRequest;
use Payconn\Garanti\Request\CancelRequest;
use Payconn\Garanti\Request\PurchaseRequest;
use Payconn\Garanti\Request\RefundRequest;

class Garanti extends AbstractGateway
{
    public function initialize(): void
    {
        $this->setBaseUrl((new BaseUrl())
            ->setProdUrls('https://sanalposprov.garanti.com.tr/VPServlet', '')
            ->setTestUrls('https://sanalposprovtest.garanti.com.tr/VPServlet', 'https://sanalposprovtest.garanti.com.tr/servlet/gt3dengine'));
    }

    public function purchase(PurchaseInterface $purchase): ResponseInterface
    {
        return $this->createRequest(PurchaseRequest::class, $purchase);
    }

    public function refund(RefundInterface $refund): ResponseInterface
    {
        return $this->createRequest(RefundRequest::class, $refund);
    }

    public function cancel(CancelInterface $cancel): ResponseInterface
    {
        return $this->createRequest(CancelRequest::class, $cancel);
    }

    public function authorize(AuthorizeInterface $authorize): ResponseInterface
    {
        return $this->createRequest(AuthorizeRequest::class, $authorize);
    }

    public function complete(CompleteInterface $complete): ResponseInterface
    {
    }
}
