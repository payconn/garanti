<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Garanti\Model\Authorize;
use Payconn\Garanti\Model\Cancel;
use Payconn\Garanti\Model\Purchase;
use Payconn\Garanti\Model\Refund;

abstract class GarantiRequest extends AbstractRequest
{
    public function getMode(): string
    {
        return $this->getModel()->isTestMode() ? 'TEST' : 'PROD';
    }

    public function getAmount(): ?float
    {
        $model = $this->getModel();
        if ($model instanceof Purchase
        || $model instanceof Authorize
        || $model instanceof Refund
        || $model instanceof Cancel) {
            return $model->getAmount() * 100;
        }

        return null;
    }
}
