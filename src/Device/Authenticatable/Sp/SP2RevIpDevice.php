<?php

namespace BroadlinkApi\Device\Authenticatable\Sp;

class SP2RevIpDevice extends SP2Device
{
    public function postDiscovery(): void
    {
        if ($this->ip !== null && strpos($this->ip, ".") !== false) {
            $this->ip = implode(".", array_reverse(explode(".", $this->ip)));
        }
    }
}
