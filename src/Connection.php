<?php

namespace BroadlinkApi;

use BroadlinkApi\Device\NetDeviceInterface;
use BroadlinkApi\Packet\Packet;

class Connection
{
    /**
     * Timeout in seconds
     */
    private const DEFAULT_TIMEOUT = 2;

    private $socket;

    private function open(): self
    {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_set_option($this->socket, SOL_SOCKET, SO_BROADCAST, 1);

        return $this;
    }

    public function sendPacketToDeviceArray(
        Packet $packet,
        NetDeviceInterface $device,
        int $timeout = self::DEFAULT_TIMEOUT
    ): \Generator {
        $this->open();

        socket_sendto($this->socket, (string)$packet, $packet->getSize(), 0, $device->getIp(), $device->getPort());
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, ['sec'=> $timeout, 'usec' => 0]);

        while($response = @socket_read($this->socket, 1024, 0)) {
            yield Packet::createFromString($response);
        }

        $this->close();
    }

    private function close(): void
    {
        socket_close($this->socket);
    }
}
