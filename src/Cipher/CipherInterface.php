<?php

namespace BroadlinkApi\Cipher;

use BroadlinkApi\Packet\Packet;

interface CipherInterface
{
    public function encrypt(Packet $data): Packet;

    public function decrypt(Packet $data): Packet;
}
