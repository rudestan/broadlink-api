<?php

namespace BroadlinkApi\Command\Authenticated;

use BroadlinkApi\Command\EncryptedCommandInterface;
use BroadlinkApi\Device\AuthenticatableDeviceInterface;
use BroadlinkApi\Command\CommandInterface;
use BroadlinkApi\Device\NetDeviceInterface;
use BroadlinkApi\Packet\Packet;

abstract class AbstractAuthenticatedDeviceCommand implements EncryptedCommandInterface
{
    /**
     * @var AuthenticatableDeviceInterface
     */
    protected $device;

    public function __construct(AuthenticatableDeviceInterface $device)
    {
        $this->device = $device;
    }

    public function getCommandId(): int
    {
        return CommandInterface::COMMAND_GET_INFO;
    }

    public function getDevice(): NetDeviceInterface
    {
        return $this->device;
    }

    public function handleResponse(Packet $packet)
    {
        return $packet;
    }
}
