<?php

namespace Payconn\Garanti\Model;

use Payconn\Common\AbstractModel;
use Payconn\Common\Model\CancelInterface;
use Payconn\Common\Traits\Amount;
use Payconn\Common\Traits\OrderId;

class Cancel extends AbstractModel implements CancelInterface
{
    use OrderId;
    use Amount;

    private $returnedOrderId;

    public function getReturnedOrderId(): string
    {
        return $this->returnedOrderId;
    }

    public function setReturnedOrderId(string $returnedOrderId): void
    {
        $this->returnedOrderId = $returnedOrderId;
    }
}
