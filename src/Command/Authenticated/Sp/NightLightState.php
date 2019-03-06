<?php

namespace BroadlinkApi\Command\Authenticated\Sp;

use BroadlinkApi\Command\Authenticated\AbstractAuthenticatedDeviceCommand;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

class NightLightState extends AbstractAuthenticatedDeviceCommand
{
    public function getPayload(): Packet
    {
        return PacketBuilder::create(0x16)->writeByte(0x00, 0x01)->getPacket();
    }

    public function handleResponse(Packet $packet)
    {
        $pb = new PacketBuilder($packet);

        if (!$pb->hasError()) {
            $state = $pb->readInt16(0x04);

            return in_array($state, [0x02, 0x03, 0xFF]);
        }

        return false;
    }
}
