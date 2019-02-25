<?php

namespace BroadlinkApi\Device;

use BroadlinkApi\Cipher\CipherInterface;
use BroadlinkApi\Cipher\Cipher;

final class NetDevice implements NetDeviceInterface
{
    public function getIp(): string
    {
        return self::DEFAULT_IP;
    }

    public function getPort(): int
    {
        return self::DEFAULT_PORT;
    }

    public function getMac(): string
    {
        return '';
    }

    public function getCipher(): CipherInterface
    {
        return new Cipher(self::BASE_KEY, self::BASE_IV);
    }
}
