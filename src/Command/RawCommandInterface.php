<?php

namespace BroadlinkApi\Command;

use BroadlinkApi\Packet\Packet;

interface RawCommandInterface extends CommandInterface
{
    public function getPacket(): Packet;
}
