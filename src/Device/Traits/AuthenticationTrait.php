<?php

namespace BroadlinkApi\Device\Traits;

use BroadlinkApi\Device\IdentifiedDevice;
use BroadlinkApi\Device\AuthenticatedDevice;
use BroadlinkApi\Command\AuthenticateCommand;
use BroadlinkApi\Device\RMDevice;

trait AuthenticationTrait
{
    use ProtocolTrait;

    public function authenticate(IdentifiedDevice $device): AuthenticatedDevice
    {
                //$authenticatedClass = AuthenticatedDevice::class


        $authCommand = new AuthenticateCommand($device, $authenticatedClass);

        return $this->protocol->executeCommand($authCommand)->current();
    }

    private function getDeviceClass(int $deviceId): ?string
    {
        if (\in_array($deviceId, [
            0x2712, 0x2737, 0x273d, 0x2783, 0x277c, 0x272a, 0x2787, 0x278b, 0x278f, 0x279d
        ], true)
        ) {
            return RMDevice::class;
        }

        return null;
    }
}
