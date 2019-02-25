<?php

namespace DS\Broadlink\Cipher;

use DS\Broadlink\Packet\Packet;

interface CipherInterface
{
    public function encrypt(Packet $data): Packet;

    public function decrypt(Packet $data): Packet;
}
