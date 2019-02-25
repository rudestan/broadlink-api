<?php

namespace BroadlinkApi\Command;

use BroadlinkApi\Device\AuthenticatedDevice;
use BroadlinkApi\Device\Device;
use BroadlinkApi\Device\DeviceInterface;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

class GetSensorsCommand implements EncryptedCommandInterface
{
    /**
     * @var Device
     */
    private $device;

    public function __construct(AuthenticatedDevice $device)
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
            'temperature'=> $pb->readFloat16(0x4)
        ];
    }

    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }

    public function getPayload(): Packet
    {
        return PacketBuilder::create(0x16)->writeByte(0x00,0x01)->getPacket();
    }
}
