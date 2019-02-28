<?php

namespace BroadlinkApi\Command\Authenticated;

use BroadlinkApi\Device\AuthenticatableDeviceInterface;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

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

    public function getPayload(): Packet
    {
        return PacketBuilder::create(4)
            ->writeByte(0x00, 0x002)
            ->attachPacket($this->command)
            ->getPacket();
    }
}
