<?php

namespace BroadlinkApi\Service;

use BroadlinkApi\Device\AuthenticatedDevice;
use BroadlinkApi\Command\AuthenticateCommand;
use BroadlinkApi\Device\NetDeviceInterface;
use BroadlinkApi\Protocol;
use BroadlinkApi\Device\RMProPlusDevice;
use BroadlinkApi\Device\RMDevice;

class DeviceAuthenticator
{
    /**
     * @var Protocol
     */
    private $protocol;

    public function __construct()
    {
        $this->protocol = new Protocol();
    }

    public function authenticate(
        NetDeviceInterface $device,
        $authenticatedClass = AuthenticatedDevice::class
    ): AuthenticatedDevice
    {
        $discoverCommand = new AuthenticateCommand($device, $authenticatedClass);

        return $this->protocol->executeCommand($discoverCommand)->current();
    }

    private function getDeviceClass(int $deviceId): ?string
    {
        if ($deviceId === 0x279d) {
            return RMProPlusDevice::class;
        }

        if (\in_array($deviceId, [
            0x2712, 0x2737, 0x273d, 0x2783, 0x277c, 0x272a, 0x2787, 0x278b, 0x278f
        ], true)
        ) {
            return RMDevice::class;
        }

        return null;
    }
}
