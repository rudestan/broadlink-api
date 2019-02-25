<?php

namespace BroadlinkApi\Command;

use BroadlinkApi\Packet\Packet;

interface EncryptedCommandInterface extends CommandInterface
{
    public function getPayload(): Packet;
}
