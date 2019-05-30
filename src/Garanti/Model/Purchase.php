<?php

namespace Payconn\Garanti\Model;

class Purchase extends GarantiModel
{
    private $installment;

    private $email;

    private $orderId;

    public function getInstallment(): int
    {
        return $this->installment;
    }

    public function setInstallment(int $installment): self
    {
        $this->installment = $installment;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }
}
