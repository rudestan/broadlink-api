<?php

namespace BroadlinkApi\Device\Authenticatable\Sp;

use BroadlinkApi\Command\Authenticated\Sp\NightLightCommand;
use BroadlinkApi\Command\Authenticated\Sp\NightLightState;
use BroadlinkApi\Command\Authenticated\Sp\PowerCommand;
use BroadlinkApi\Command\Authenticated\Sp\PowerStateCommand;
use BroadlinkApi\Device\AbstractAuthenticatableDevice;

/**
 * Nightlight - is a button on Smart Plug with built-in led. It might be useful if it is powered on during the night.
 */
class SP2Device extends AbstractAuthenticatableDevice
{
    public function getType(): string
    {
        return self::TYPE_SP;
    }

    public function powerOn(): void
    {
        $this->protocol->executeCommand(new PowerCommand($this, true))->current();
    }

    public function powerOff(): void
    {
        $this->protocol->executeCommand(new PowerCommand($this, false))->current();
    }
    
    public function isPoweredOn(): bool
    {
        return $this->protocol->executeCommand(new PowerStateCommand($this))->current();
    }

    public function nightLightOn(): void
    {
        $isPoweredOn = $this->isPoweredOn();

        $this->protocol->executeCommand(new NightlightCommand($this, $isPoweredOn, true))->current();
    }

    public function nightLightOff(): void
    {
        $isPoweredOn = $this->isPoweredOn();

        $this->protocol->executeCommand(new NightlightCommand($this, $isPoweredOn, false))->current();
    }

    public function isNightLightOn(): bool
    {
        return $this->protocol->executeCommand(new NightLightState($this))->current();
    }
}
