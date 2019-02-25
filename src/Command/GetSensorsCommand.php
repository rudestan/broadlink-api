<?php

namespace DS\Broadlink\Command;

use DS\Broadlink\Device\AuthenticatedDevice;
use DS\Broadlink\Device\Device;
use DS\Broadlink\Device\DeviceInterface;
use DS\Broadlink\Packet\Packet;
use DS\Broadlink\Packet\PacketBuilder;

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
