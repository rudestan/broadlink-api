<?php

namespace BroadlinkApi\Command;

use BroadlinkApi\Device\IdentifiedDeviceInterface;
use BroadlinkApi\Device\NetDeviceInterface;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

class GetSensorsCommand implements EncryptedCommandInterface
{
    /**
     * @var IdentifiedDeviceInterface
     */
    private $device;

    public function __construct(IdentifiedDeviceInterface $device)
    {
        $this->device = $device;
    }

    public function getCommandId(): int
    {
        return CommandInterface::COMMAND_GET_INFO;
    }

    public function handleResponse(Packet $packet): array
    {
        $pb = new PacketBuilder($packet);

        return [
            'temperature'=> $pb->readFloat16(0x4),
            'humidity' => $pb->readFloat16(0x6)
        ];
    }

    public function getDevice(): NetDeviceInterface
    {
        return $this->device;
    }

    public function getPayload(): Packet
    {
        return PacketBuilder::create(0x16)->writeByte(0x00, 0x01)->getPacket();
    }
}
