<?php

namespace DS\Broadlink\Command;

use DS\Broadlink\Packet\Packet;

interface EncryptedCommandInterface extends CommandInterface
{
    public function getPayload(): Packet;
}
