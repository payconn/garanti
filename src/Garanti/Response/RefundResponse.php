<?php

namespace Payconn\Garanti\Response;

use Payconn\Common\AbstractResponse;

class RefundResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return '00' == strval($this->getParameters()->get('Transaction')->Response->ReasonCode);
    }

    public function getResponseMessage(): string
    {
        if ($this->isSuccessful()) {
            return strval($this->getParameters()->get('Transaction')->Response->Message);
        }

        return strval($this->getParameters()->get('Transaction')->Response->ErrorMsg).':'.strval($this->getParameters()->get('Transaction')->Response->SysErrMsg);
    }

    public function getResponseCode(): string
    {
        return strval($this->getParameters()->get('Transaction')->Response->Code);
    }

    public function getResponseBody(): array
    {
        return $this->getParameters()->all();
    }

    public function isRedirection(): bool
    {
        return false;
    }

    public function getRedirectForm(): ?string
    {
        return null;
    }
}
