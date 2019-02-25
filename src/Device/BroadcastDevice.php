<?php

namespace TPG\Broadlink\Device;


use TPG\Broadlink\Cipher\Cipher;
use TPG\Broadlink\Cipher\CipherInterface;

final class BroadcastDevice implements DeviceInterface
{
    public function getMac(): string
    {
        return '';
    }

    public function getIP(): string
    {
        return '255.255.255.255';
    }

    public function getPort(): int
    {
        return 80;
    }

    public function getCipher(): CipherInterface
    {
        return new Cipher(self::BASE_KEY,self::BASE_IV);
    }
}