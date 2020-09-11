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

    protected string $returnedOrderId;

    protected string $type = 'void';

    protected string $userId = 'PROVRFN';

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
