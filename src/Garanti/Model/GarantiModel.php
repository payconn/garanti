<?php

namespace Payconn\Garanti\Model;

use Payconn\Common\AbstractModel;

abstract class GarantiModel extends AbstractModel
{
    public function getMode(): string
    {
        if ($this->isTestMode()) {
            return 'TEST';
        }

        return 'PROD';
    }
}
