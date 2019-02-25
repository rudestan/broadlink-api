<?php

namespace DS\Broadlink\Service;

use DS\Broadlink\Device\DeviceInterface;
use DS\Broadlink\Device\AuthenticatedDevice;
use DS\Broadlink\Command\AuthenticateCommand;
use DS\Broadlink\Protocol;
use DS\Broadlink\Device\RMProPlusDevice;
use DS\Broadlink\Device\RMDevice;

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
        DeviceInterface $device,
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
