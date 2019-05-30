<?php

namespace Payconn;

use Payconn\Common\AbstractGateway;
use Payconn\Common\ModelInterface;
use Payconn\Common\ResponseInterface;
use Payconn\Garanti\Model\Purchase;
use Payconn\Garanti\Request\PurchaseRequest;

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
    }

    public function authorizeComplete(ModelInterface $model): ResponseInterface
    {
        // TODO: Implement authorizeComplete() method.
    }

    public function overrideBaseUrl(ModelInterface $model): void
    {
        if ($model instanceof Purchase) {
            if ($model->isTestMode()) {
                $model->setBaseUrl('https://sanalposprovtest.garanti.com.tr/VPServlet');
            } else {
                $model->setBaseUrl('https://sanalposprov.garanti.com.tr/VPServlet');
            }
        }
    }
}
