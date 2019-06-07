<?php

namespace Payconn\Garanti\Model;

use Payconn\Common\AbstractModel;
use Payconn\Common\Model\AuthorizeInterface;
use Payconn\Common\Traits\Amount;
use Payconn\Common\Traits\CreditCard;
use Payconn\Common\Traits\Currency;
use Payconn\Common\Traits\Installment;
use Payconn\Common\Traits\OrderId;
use Payconn\Common\Traits\ReturnUrl;

class Authorize extends AbstractModel implements AuthorizeInterface
{
    use CreditCard;
    use Amount;
    use Installment;
    use Currency;
    use ReturnUrl;
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
