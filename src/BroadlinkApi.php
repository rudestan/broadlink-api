<?php

namespace BroadlinkApi;

use BroadlinkApi\Command\AuthenticateCommand;
use BroadlinkApi\Command\DiscoverCommand;
use BroadlinkApi\Device\AuthenticatedDevice;
use BroadlinkApi\Device\DeviceInterface;

class BroadlinkApi
{
    /**
     * @var Protocol
     */
    private $protocol;

    public function __construct()
    {
        $this->protocol = new Protocol();
    }

    public function discover(): array
    {
        $discoverCommand = new DiscoverCommand();
        $devices = [];

        foreach($this->protocol->executeCommand($discoverCommand) as $device){
            $devices[] = $device;
        }

        return $devices;
    }

    public function authenticate(
        DeviceInterface $device,
        $authenticatedClass = AuthenticatedDevice::class
    ): AuthenticatedDevice
    {
        $discoverCommand = new AuthenticateCommand($device, $authenticatedClass);

        return $this->protocol->executeCommand($discoverCommand)->current();
    }
}
