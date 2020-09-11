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

    protected string $returnedOrderId;

    protected string $userId = 'PROVRFN';

    protected string $type = 'refund';

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getReturnedOrderId(): string
    {
        return $this->returnedOrderId;
    }

    public function setReturnedOrderId(string $returnedOrderId): void
    {
        $this->returnedOrderId = $returnedOrderId;
    }
}
