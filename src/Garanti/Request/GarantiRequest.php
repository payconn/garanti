<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Garanti\Model\Complete;

abstract class GarantiRequest extends AbstractRequest
{
    public function getMode(): string
    {
        return $this->getModel()->isTestMode() ? 'TEST' : 'PROD';
    }

    public function getAmount(): ?float
    {
        if (false === $this->getModel() instanceof Complete) {
            return $this->getModel()->getAmount() * 100;
        }

        return null;
    }
}
