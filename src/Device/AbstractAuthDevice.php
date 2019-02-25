<?php

namespace BroadlinkApi\Device;

use BroadlinkApi\Cipher\CipherInterface;
use BroadlinkApi\Cipher\Cipher;

abstract class AbstractAuthDevice implements IdentifiedDeviceInterface
{
    /**
     * @var int
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
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var int
     */
    protected $sessionId;

    /**
     * @var CipherInterface
     */
    protected $cipher;

    public function __construct(
        string $ip,
        string $mac,
        int $deviceId,
        string $name,
        string $model,
        int $sessionId,
        array $key,
        array $vector = self::BASE_IV
    ) {
        $this->ip = $ip;
        $this->mac = $mac;
        $this->deviceId = $deviceId;
        $this->name = $name;
        $this->model = $model;
        $this->sessionId = $sessionId;
        $this->cipher = new Cipher($key, $vector);
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getMac(): string
    {
        return $this->mac;
    }

    public function getId(): int
    {
        return $this->deviceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPort(): int
    {
        return self::DEFAULT_PORT;
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
