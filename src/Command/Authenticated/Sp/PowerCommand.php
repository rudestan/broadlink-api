<?php

namespace BroadlinkApi\Command\Authenticated\Sp;

use BroadlinkApi\Command\Authenticated\AbstractAuthenticatedDeviceCommand;
use BroadlinkApi\Device\AuthenticatableDeviceInterface;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

class PowerCommand extends AbstractAuthenticatedDeviceCommand
{
    /**
     * @var bool
     */
    private $state;

    public function __construct(AuthenticatableDeviceInterface $device, bool $state)
    {
        parent::__construct($device);

        $this->state = $state;
    }

    public function getPayload(): Packet
    {
        return PacketBuilder::create(0x16)
            ->writeByte(0x00, 0x02)
            ->writeByte(0x04, $this->state ? 0x01 : 0x00)
            ->getPacket()
        ;
    }
}
/*def set_power(self, state):
    """Sets the power state of the smart plug."""
    packet = bytearray(16)
    packet[0] = 2
    if self.check_nightlight():
        packet[4] = 3 if state else 2
    else:
        packet[4] = 1 if state else 0
    self.send_packet(0x6a, packet)*/