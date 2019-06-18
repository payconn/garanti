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
        if ($this->getModel() instanceof Purchase
        || $this->getModel() instanceof Authorize
        || $this->getModel() instanceof Refund
        || $this->getModel() instanceof Cancel) {
            return $this->getModel()->getAmount() * 100;
        }

        return null;
    }
}
