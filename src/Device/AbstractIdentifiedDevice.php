<?php

namespace BroadlinkApi\Device;

use BroadlinkApi\Dto\AuthDataDto;
use BroadlinkApi\Cipher\CipherInterface;
use BroadlinkApi\Cipher\Cipher;
use BroadlinkApi\Command\AuthenticateCommand;
use BroadlinkApi\Protocol;
use BroadlinkApi\Exception\ProtocolException;
use BroadlinkApi\Service\DeviceFactory;

abstract class AbstractIdentifiedDevice implements IdentifiedDeviceInterface
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

    /**
     * @var Protocol
     */
    protected $protocol;

    /**
     * @var bool
     */
    protected $isAuthenticated = false;

    public function __construct(
        string $ip,
        string $mac,
        int $deviceId,
        string $name,
        string $model
    ) {
        $this->ip = $ip;
        $this->mac = $mac;
        $this->deviceId = $deviceId;
        $this->name = $name;
        $this->model = $model;
        $this->protocol = new Protocol();
        $this->cipher = new Cipher(self::BASE_KEY, self::BASE_IV);
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

    public function getSessionId(): int
    {
        return $this->sessionId;
    }

    public function getCipher(): CipherInterface
    {
        return $this->cipher;
    }

    public function getProtocol(): Protocol
    {
        return $this->protocol;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    /**
     * @throws ProtocolException
     */
    public function authenticate()
    {
        $this->isAuthenticated = false;

        $authData = $this->protocol
            ->executeCommand(new AuthenticateCommand($this))
            ->current();

        if ($authData instanceof AuthDataDto) {
            $this->cipher = new Cipher($authData->getKey(), self::BASE_IV);
            $this->sessionId = $authData->getSessionId();
            $this->isAuthenticated = true;
        }
    }
}
