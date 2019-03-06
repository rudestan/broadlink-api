<?php

namespace BroadlinkApi\Device;

use BroadlinkApi\Exception\ProtocolException;
use BroadlinkApi\Command\AuthenticateCommand;
use BroadlinkApi\Dto\AuthDataDto;
use BroadlinkApi\Cipher\Cipher;

abstract class AbstractAuthenticatableDevice extends AbstractIdentifiedDevice implements AuthenticatableDeviceInterface
{
    /**
     * @var int|null
     */
    protected $sessionId;

    public function getSessionId(): ?int
    {
        return $this->sessionId;
    }

    public function isAuthenticated(): bool
    {
        return $this->sessionId !== null;
    }

    public function isAuthenticatable(): bool
    {
        return true;
    }

    /**
     * @throws ProtocolException
     */
    public function authenticate(): bool
    {
        $this->sessionId = null;

        $authData = $this->protocol
            ->executeCommand(new AuthenticateCommand($this))
            ->current();

        if ($authData instanceof AuthDataDto) {
            $this->cipher = new Cipher($authData->getKey(), self::BASE_IV);
            $this->sessionId = $authData->getSessionId();
        }

        return $this->isAuthenticated();
    }
}
