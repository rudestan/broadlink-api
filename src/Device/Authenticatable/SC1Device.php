<?php

namespace BroadlinkApi\Device\Authenticatable;

use BroadlinkApi\Device\Authenticatable\Sp\SP2RevIpDevice;

class SC1Device extends SP2RevIpDevice
{
    public function getType(): string
    {
        return self::TYPE_SC1;
    }
}
