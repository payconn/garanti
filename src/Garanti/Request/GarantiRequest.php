<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\AbstractRequest;

abstract class GarantiRequest extends AbstractRequest
{
    public function getMode(): string
    {
        return $this->getModel()->isTestMode() ? 'TEST' : 'PROD';
    }
}
