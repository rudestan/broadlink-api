<?php

namespace BroadlinkApi\Command\Authenticated;

use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

class CheckLearnedCommand extends AbstractAuthenticatedDeviceCommand
{
    public function getPayload(): Packet
    {
        return PacketBuilder::create(0x16)->writeByte(0x00, 0x04)->getPacket();
    }

    public function handleResponse(Packet $packet)
    {
        return (new PacketBuilder($packet))->extractFromIndex(4);
    }
}
