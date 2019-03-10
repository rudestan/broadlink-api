<?php

namespace BroadlinkApi\Command;

use BroadlinkApi\Device\NetDeviceInterface;
use BroadlinkApi\Exception\CommandException;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Packet\PacketBuilder;
use BroadlinkApi\Service\DeviceFactory;
use BroadlinkApi\Utils;

class DiscoverCommand implements RawCommandInterface
{
    /**
     * @var string
     */
    private $localIp;

    /**
     * @var NetDeviceInterface
     */
    private $device;

    /**
     * @var DeviceFactory
     */
    private $deviceFactory;

    /**
     * @throws CommandException
     */
    public function __construct(NetDeviceInterface $device, string $localIp = null)
    {
        $this->device = $device;
        $this->deviceFactory = new DeviceFactory(true);

        if($localIp === null) {
            $this->localIp = Utils::getLocalIp();
        } else {
            if(!filter_var($localIp, FILTER_VALIDATE_IP)) {
                throw new CommandException('Invalid local IP address ('. $localIp. ')');
            }

            $this->localIp = $localIp;
        }
    }

    public function getCommandId(): int
    {
        return CommandInterface::COMMAND_DISCOVER;
    }

    public function getDevice(): NetDeviceInterface
    {
        return $this->device;
    }

    public function getPacket(): Packet
    {
        $packetBuilder = PacketBuilder::create(0x30);
        $dt = new \DateTime();
        $timeZoneDiff = (int)($dt->format('Z') / 3600);

        $packetBuilder->writeInt32(0x08,$timeZoneDiff);
        $packetBuilder->writeInt16(0x0c,(int) $dt->format('Y'));
        $packetBuilder->writeBytes(
            0x0e,
            [
                (int) $dt->format('H') - $timeZoneDiff,
                (int) $dt->format('i'),
                (int) $dt->format('s')
            ]
        );
        $packetBuilder->writeBytes(
            0x10,
            [
                (int) $dt->format('m'),
                (int) $dt->format('d'),
                (int) $dt->format('N'),
                (int) $dt->format('m')
            ]
        );
        $packetBuilder->writeBytes(0x18, array_reverse(Utils::getIPAddressArray($this->localIp)));

        return $packetBuilder->getPacket();
    }

    public function handleResponse(Packet $packet)
    {
        $packetBuilder = new PacketBuilder($packet);
        $deviceId = $packetBuilder->readInt16(0x34);
        $ip = implode('.',$packetBuilder->readBytes(0x36,4));
        $mac = vsprintf('%02x:%02x:%02x:%02x:%02x:%02x',$packetBuilder->readBytes(0x3a,6));
        $name =  trim(implode(array_map('\chr',array_reverse($packetBuilder->readBytes(0x40,60)))));

        return $this->deviceFactory->create($ip, $mac, $deviceId, $name);
    }
}
