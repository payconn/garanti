<?php

namespace Payconn\Garanti;

use Payconn\Common\TokenInterface;

class Token implements TokenInterface
{
    private string $terminalId;

    private string $merchantId;

    private string $password;

    private ?string $storeKey;

    public function __construct(string $terminalId, string $merchantId, string $password, string $storeKey = null)
    {
        $this->terminalId = $terminalId;
        $this->merchantId = $merchantId;
        $this->password = $password;
        $this->storeKey = $storeKey;
    }

    public function getTerminalId(): string
    {
        return $this->terminalId;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getStoreKey(): ?string
    {
        return $this->storeKey;
    }
}
