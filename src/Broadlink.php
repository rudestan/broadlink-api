<?php

namespace BroadlinkApi;

use BroadlinkApi\Command\AuthenticateCommand;
use BroadlinkApi\Command\DiscoverCommand;
use BroadlinkApi\Device\AuthenticatedDevice;
use BroadlinkApi\Device\DeviceInterface;

class Broadlink
{
    public static function discover(): array
    {
        $protocol = Protocol::create();
        $discoverCommand = new DiscoverCommand();
        $devices = [];

        foreach($protocol->executeCommand($discoverCommand) as $device){
            $devices[] = $device;
        }

        return $devices;
    }

    public static function authenticate(
        DeviceInterface $device,
        $authenticatedClass = AuthenticatedDevice::class
    ): AuthenticatedDevice
    {
        $protocol = Protocol::create();
        $discoverCommand = new AuthenticateCommand($device,$authenticatedClass);

        return $protocol->executeCommand($discoverCommand)->current();
    }
}
