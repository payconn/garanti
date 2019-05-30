<?php

namespace Payconn\Garanti\Model;

class Refund extends GarantiModel
{
    private $orderId;

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
