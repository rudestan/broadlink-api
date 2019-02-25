<?php

namespace BroadlinkApi\Device;

use BroadlinkApi\Cipher\Cipher;

class UnknownDevice extends AbstractAuthDevice
{
    public function __construct(
        string $ip,
        string $mac,
        int $deviceId,
        string $name,
        string $model,
        int $sessionId
    ) {
        $this->ip = $ip;
        $this->mac = $mac;
        $this->deviceId = $deviceId;
        $this->name = $name;
        $this->model = $model;
        $this->sessionId = $sessionId;
        $this->cipher = new Cipher(self::BASE_KEY, self::BASE_IV);
    }

    public function getPort(): int
    {
        return self::DEFAULT_PORT;
    }
}
