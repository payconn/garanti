<?php

namespace Payconn;

use Payconn\Common\AbstractGateway;
use Payconn\Common\ModelInterface;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Model\Purchase;
use Payconn\Garanti\Model\Refund;
use Payconn\Garanti\Request\PurchaseRequest;
use Payconn\Garanti\Request\RefundRequest;

class Garanti extends AbstractGateway
{
    public function authorize(ModelInterface $model): ResponseInterface
    {
    }

    public function purchase(ModelInterface $model): ResponseInterface
    {
        $this->overrideBaseUrl($model);

        return ($this->createRequest(PurchaseRequest::class, $model))->send();
    }

    public function purchaseComplete(ModelInterface $model): ResponseInterface
    {
    }

    public function refund(ModelInterface $model): ResponseInterface
    {
        $this->overrideBaseUrl($model);

        return ($this->createRequest(RefundRequest::class, $model))->send();
    }

    public function authorizeComplete(ModelInterface $model): ResponseInterface
    {
        // TODO: Implement authorizeComplete() method.
    }

    public function overrideBaseUrl(ModelInterface $model): void
    {
        if ($model instanceof Purchase
        || $model instanceof Refund) {
            if ($model->isTestMode()) {
                $model->setBaseUrl('https://sanalposprovtest.garanti.com.tr/VPServlet');
            } else {
                $model->setBaseUrl('https://sanalposprov.garanti.com.tr/VPServlet');
            }
        }
    }
}
