<?php

namespace BroadlinkApi\Command\Authenticated;

use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

class GetSensorsCommand extends AbstractAuthenticatedDeviceCommand
{
    public function getPayload(): Packet
    {
        return PacketBuilder::create(0x16)->writeByte(0x00, 0x01)->getPacket();
    }

    public function handleResponse(Packet $packet): array
    {
        $pb = new PacketBuilder($packet);

        return [
            'temperature'=> $pb->readFloat16(0x4),
            'humidity' => $pb->readFloat16(0x6)
        ];
    }
}
