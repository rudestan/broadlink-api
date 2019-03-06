<?php

namespace BroadlinkApi\Command\Authenticated\Sp;

use BroadlinkApi\Command\Authenticated\AbstractAuthenticatedDeviceCommand;
use BroadlinkApi\Device\AuthenticatableDeviceInterface;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

class NightLightCommand extends AbstractAuthenticatedDeviceCommand
{
    /**
     * @var bool
     */
    private $state;

    /**
     * @var bool
     */
    private $poweredOn;

    public function __construct(AuthenticatableDeviceInterface $device, bool $poweredOn, bool $state)
    {
        parent::__construct($device);

        $this->poweredOn = $poweredOn;
        $this->state = $state;
    }

    public function getPayload(): Packet
    {
        $state = $this->state ? 2 : 0;

        if ($this->poweredOn) {
            $state = $this->state ? 3 : 1;
        }

        return PacketBuilder::create(0x16)
            ->writeByte(0x00, 0x02)
            ->writeByte(0x04, $state)
            ->getPacket()
        ;
    }
}
