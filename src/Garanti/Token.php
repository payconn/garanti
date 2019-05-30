<?php

namespace Payconn\Garanti;

use Payconn\Common\TokenInterface;

class Token implements TokenInterface
{
    private $terminalId;

    private $merchantId;

    private $userId;

    private $password;

    public function __construct(string $terminalId, string $merchantId, string $userId, string $password)
    {
        $this->terminalId = $terminalId;
        $this->merchantId = $merchantId;
        $this->userId = $userId;
        $this->password = $password;
    }

    public function getTerminalId(): string
    {
        return $this->terminalId;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
