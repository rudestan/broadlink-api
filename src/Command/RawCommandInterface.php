<?php

namespace DS\Broadlink\Command;

use DS\Broadlink\Packet\Packet;

interface RawCommandInterface extends CommandInterface
{
    public function getPacket(): Packet;
}
