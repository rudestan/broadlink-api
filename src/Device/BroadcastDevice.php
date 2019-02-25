<?php

namespace DS\Broadlink\Device;

use DS\Broadlink\Cipher\Cipher;
use DS\Broadlink\Cipher\CipherInterface;

/**
 * @TODO: Remove for simplicity
 */
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
        return self::DEFAULT_PORT;
    }

    public function getCipher(): CipherInterface
    {
        return new Cipher(self::BASE_KEY, self::BASE_IV);
    }
}
