<?php

namespace DS\Broadlink\Device;

use DS\Broadlink\Broadlink;
use DS\Broadlink\Cipher\Cipher;
use DS\Broadlink\Cipher\CipherInterface;

class AuthenticatedDevice implements DeviceInterface
{
    /**
     * @var DeviceInterface
     */
    private $device;

    /**
     * @var array
     */
    private $key;

    /**
     * @var array
     */
    private $vector;

    /**
     * @var int
     */
    private $sessionId;

    public function __construct(DeviceInterface $device, int $sessionId, array $key, array $vector = self::BASE_IV)
    {
        $this->device = $device;
        $this->key = $key;
        $this->vector = $vector;
        $this->sessionId = $sessionId;
    }

    public function getMac(): string
    {
        return $this->device->getMac();
    }

    public function getIP(): string
    {
        return $this->device->getIP();
    }

    public function getPort(): int
    {
        return $this->device->getPort();
    }

    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }

    public function getCipher(): CipherInterface
    {
        return new Cipher($this->key, $this->vector);
    }

    public function getSessionId(): int
    {
        return $this->sessionId;
    }

    public static function authenticate(string $ip, string $mac): AuthenticatedDevice
    {
        return Broadlink::authenticate(new Device($ip, $mac), static::class);
    }
}
