<?php

namespace BroadlinkApi\Device\Identified;

use BroadlinkApi\Exception\ProtocolException;
use BroadlinkApi\Command\GetSensorsCommand;

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
