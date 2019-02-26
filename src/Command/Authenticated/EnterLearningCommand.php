<?php

namespace BroadlinkApi\Command\Authenticated;

use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

class EnterLearningCommand extends AbstractAuthenticatedDeviceCommand
{
    public function getPayload(): Packet
    {
        return PacketBuilder::create(0x16)->writeByte(0x00, 0x03)->getPacket();
    }
}
