<?php

namespace Payconn\Garanti\Model;

use Payconn\Common\AbstractModel;
use Payconn\Common\Model\RefundInterface;
use Payconn\Common\Traits\Amount;
use Payconn\Common\Traits\OrderId;

class Refund extends AbstractModel implements RefundInterface
{
    use Amount;
    use OrderId;

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
