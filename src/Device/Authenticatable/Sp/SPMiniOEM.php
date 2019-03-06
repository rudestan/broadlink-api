<?php

namespace BroadlinkApi\Device\Authenticatable\Sp;

class SPMiniOEM extends SP2Device
{
    public function init(): void
    {
        // Device with ID 0x2733 returns normal IP address so it needs to be reversed again
        if ($this->ip !== null && strpos($this->ip, ".") !== false) {
            $this->ip = implode(".", array_reverse(explode(".", $this->ip)));
        }
    }
}