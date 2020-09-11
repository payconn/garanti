<?php

namespace Payconn\Garanti\Model;

use Payconn\Common\AbstractModel;
use Payconn\Common\Model\CompleteInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class Complete extends AbstractModel implements CompleteInterface
{
    protected ParameterBag $returnParams;

    protected string $userId = 'PROVAUT';

    protected string $type = 'sales';

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setReturnParams(array $returnParams): void
    {
        $this->returnParams = new ParameterBag($returnParams);
    }

    public function getReturnParams(): ParameterBag
    {
        return $this->returnParams;
    }
}
