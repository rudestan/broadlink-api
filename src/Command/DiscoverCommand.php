<?php

namespace DS\Broadlink\Command;

use DS\Broadlink\Device\BroadcastDevice;
use DS\Broadlink\Device\Device;
use DS\Broadlink\Device\DeviceInterface;
use DS\Broadlink\Device\DiscoveredDevice;
use DS\Broadlink\Packet\Packet;
use DS\Broadlink\Packet\PacketBuilder;
use DS\Broadlink\Utils;

class DiscoverCommand implements RawCommandInterface
{
    /**
     * @var string
     */
    private $localIp;

    /**
     * @var BroadcastDevice
     */
    private $device;

    public function __construct(string $localIp = null)
    {
        if($localIp === null) {
            $this->localIp = $this->getLocalIp();
        } else {
            if(!filter_var($localIp, FILTER_VALIDATE_IP)) {
                throw new \InvalidArgumentException('Invalid local IP address');
            }

            $this->localIp = $localIp;
        }

        $this->device = new BroadcastDevice();
    }

    public function getCommandId(): int
    {
        return CommandInterface::COMMAND_DISCOVER;
    }

    public function getPacket(): Packet
    {
        $packetBuilder = PacketBuilder::create(0x30);
        $dt = new \DateTime();
        $timeZoneDiff = (int)($dt->format('Z')/3600);

        $packetBuilder->writeInt32(0x08,$timeZoneDiff);
        $packetBuilder->writeInt16(0x0c,(int) $dt->format('Y'));
        $packetBuilder->writeBytes(
            0x0e,
            [
                (int) ($dt->format('H') - $timeZoneDiff),
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
        $packetBuilder->writeBytes(0x18, array_reverse(Utils::getIPAddressArray($this->getLocalIp())));

        return $packetBuilder->getPacket();
    }

    public function handleResponse(Packet $packet)
    {
        $packetBuilder = new PacketBuilder($packet);
        $deviceId = $packetBuilder->readInt16(0x34);
        $ip = implode('.',$packetBuilder->readBytes(0x36,4));
        $mac = vsprintf('%02x:%02x:%02x:%02x:%02x:%02x',$packetBuilder->readBytes(0x3a,6));
        $name =  trim(implode(array_map('\chr',array_reverse($packetBuilder->readBytes(0x40,60)))));

        return new DiscoveredDevice(new Device($ip, $mac), $deviceId, $name);
    }

    private function getLocalIp()
    {
        $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        socket_connect($s ,'8.8.8.8', 53);
        socket_getsockname($s, $localIp);
        socket_close($s);

        return $localIp;
    }

    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }
}
