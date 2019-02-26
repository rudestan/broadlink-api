<?php

namespace BroadlinkApi\Device\Authenticatable;

use BroadlinkApi\Command\Authenticated\GetSensorsCommand;
use BroadlinkApi\Exception\ProtocolException;

class RMDeviceSensor extends RMDevice
{
    /**
     * @throws ProtocolException
     */
    public function getTemperature()
    {
        return $this->protocol->executeCommand(new GetSensorsCommand($this))->current()['temperature'];
    }
}
