<?php

namespace BroadlinkApi\Device\Traits;

use BroadlinkApi\Command\DiscoverCommand;

trait DiscoveryTrait
{
    use ProtocolTrait;

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
