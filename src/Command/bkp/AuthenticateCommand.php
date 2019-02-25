<?php

namespace BroadlinkApi\Command;

use BroadlinkApi\Device\AuthenticatedDevice;
use BroadlinkApi\Device\NetDeviceInterface;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;

class AuthenticateCommand implements EncryptedCommandInterface
{
    /**
     * @var NetDeviceInterface
     */
    private $device;

    /**
     * @var string
     */
    private $authenticatedClass;

    public function __construct(NetDeviceInterface $device, $authenticatedClass = AuthenticatedDevice::class)
    {
        $this->device = $device;
        $this->authenticatedClass = $authenticatedClass;
    }

    public function getCommandId(): int
    {
        return CommandInterface::COMMAND_AUTHENTICATE;
    }

    public function getPayload(): Packet
    {
        return Packet::createZeroPacket(0x50);
    }

    public function handleResponse(Packet $packet): AuthenticatedDevice
    {
        $packetBuilder = new PacketBuilder($packet);
        $sessionId = $packetBuilder->readInt32(0x00);
        $key = array_reverse($packetBuilder->readBytes(0x04,16));

        return new $this->authenticatedClass($this->device, $sessionId, $key);
    }

    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }
}
