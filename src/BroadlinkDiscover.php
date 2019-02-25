<?php

namespace BroadlinkApi;

use BroadlinkApi\Command\DiscoverCommand;
use BroadlinkApi\Device\NetDevice;

class BroadlinkDiscover
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
}

/*$devices = (new NetDevice())->discover();

foreach ($devices as $device) {
    $device->authenticate();
}

///

$api = new ApiClient();



*/