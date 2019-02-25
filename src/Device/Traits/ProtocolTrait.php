<?php

namespace BroadlinkApi\Device\Traits;

use BroadlinkApi\Protocol;

trait ProtocolTrait
{
    /**
     * @var Protocol
     */
    private $protocol;

    private function getProtocol(): Protocol
    {
        if ($this->protocol === null) {
            $this->protocol = new Protocol();
        }

        return $this->protocol;
    }
}
