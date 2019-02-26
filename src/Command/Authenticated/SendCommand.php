<?php

namespace BroadlinkApi\Command\Authenticated;

use BroadlinkApi\Device\AuthenticatableDeviceInterface;
use BroadlinkApi\Packet\Packet;

class SendCommand extends AbstractAuthenticatedDeviceCommand
{
    /**
     * @var Packet
     */
    private $command;

    public function __construct(AuthenticatableDeviceInterface $device, Packet $command)
    {
        parent::__construct($device);

        $this->command = $command;
    }

    /**
     * @TODO: Refactor to fill the first 4 bytes and take the rest from original command
     */
    public function getPayload(): Packet
    {
        $this->command->offsetSet(0x00, 0x002);

        return $this->command;
    }
}
