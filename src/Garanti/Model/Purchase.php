<?php

namespace Payconn\Garanti\Model;

use Payconn\Common\AbstractModel;
use Payconn\Common\Model\PurchaseInterface;
use Payconn\Common\Traits\Amount;
use Payconn\Common\Traits\CreditCard;
use Payconn\Common\Traits\Currency;
use Payconn\Common\Traits\Installment;
use Payconn\Common\Traits\OrderId;

class Purchase extends AbstractModel implements PurchaseInterface
{
    use CreditCard;
    use Amount;
    use Currency;
    use Installment;
    use OrderId;

    protected $userId = 'PROVAUT';

    protected $type = 'sales';

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
