<?php

namespace Payconn\Garanti\Request;

use Payconn\Common\AbstractRequest;

abstract class GarantiRequest extends AbstractRequest
{
    public function getMode(): string
    {
        return $this->getModel()->isTestMode() ? 'TEST' : 'PROD';
    }

    public function getIpAddress(): string
    {
        $ipAddress = parent::getIpAddress();
        if (!$ipAddress) {
            $ipAddress = '127.0.0.1';
        }

        return $ipAddress;
    }
}
