<?php

namespace BroadlinkApi\Device;

use BroadlinkApi\Command\GetSensorsCommand;
use BroadlinkApi\Protocol;

class RMDevice extends IdentifiedDevice
{
    public function getTemperature()
    {
        return Protocol::create()->executeCommand(new GetSensorsCommand($this))->current()['temperature'];
    }
}
