<?php

namespace BroadlinkApi\Device;

use BroadlinkApi\Protocol;
use BroadlinkApi\Cipher\CipherInterface;
use BroadlinkApi\Cipher\Cipher;

abstract class AbstractIdentifiedDevice implements IdentifiedDeviceInterface
{
    /**
     * @var int|null
     */
    protected $deviceId;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $mac;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $model;

    /**
     * @var CipherInterface
     */
    protected $cipher;

    /**
     * @var Protocol
     */
    protected $protocol;

    public function __construct(
        string $ip,
        string $mac,
        ?int $deviceId = null,
        ?string $name = null,
        ?string $model = null
    ) {
        $this->ip = $ip;
        $this->mac = $mac;
        $this->deviceId = $deviceId;
        $this->name = $name;
        $this->model = $model;
        $this->protocol = new Protocol();
        $this->cipher = new Cipher(self::BASE_KEY, self::BASE_IV);
    }

    public function postDiscovery(): void {}

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getMac(): string
    {
        return $this->mac;
    }

    public function getDeviceId(): ?int
    {
        return $this->deviceId;
    }

    public function getPort(): int
    {
        return self::DEFAULT_PORT;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getCipher(): CipherInterface
    {
        return $this->cipher;
    }
}
