<?php

namespace DS\Broadlink;

use DS\Broadlink\Command\AuthenticateCommand;
use DS\Broadlink\Command\DiscoverCommand;
use DS\Broadlink\Device\AuthenticatedDevice;
use DS\Broadlink\Device\DeviceInterface;

class BroadlinkApiClient
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
