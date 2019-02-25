<?php

namespace DS\Broadlink\Device;

use DS\Broadlink\Command\GetSensorsCommand;
use DS\Broadlink\Protocol;

class RMDevice extends AuthenticatedDevice
{
    public function getTemperature()
    {
        return Protocol::create()->executeCommand(new GetSensorsCommand($this))->current()['temperature'];
    }
}
