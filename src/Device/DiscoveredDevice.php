<?php

namespace DS\Broadlink\Device;

use DS\Broadlink\Broadlink;
use DS\Broadlink\Cipher\CipherInterface;

/**
 * @TODO:
 * - Remove (BroadcastDevice.php, Device.php)
 * - Leave only DiscoveredDevice.php, AuthenticatedDevice.php, (all extended)
 * - Remove unnecessary nested passing of device references in methods
 * - Refactor workflow as follows:
 *      1) After discovering: DiscoveredDevice[]
 *      2) After authentication of each device: AuthenticatedDevice[]|Corresponding Extended Class[]
 */
class DiscoveredDevice implements DeviceInterface, \JsonSerializable
{
    public const DEVICE_UNKNOWN = 'Unknown';
    public const DEVICE_SP1 = 'SP1';
    public const DEVICE_SP2 = 'SP2';
    public const DEVICE_HSP2 = 'Honeywell SP2';
    public const DEVICE_SPM = 'SPMini';
    public const DEVICE_SP3 = 'SP3';
    public const DEVICE_SPM2 = 'SPMini2';
    public const DEVICE_OEM_SPM = 'OEM branded SPMini';
    public const DEVICE_OEM_SPM2 = 'OEM branded SPMini2';
    public const DEVICE_SPMP = 'SPMiniPlus';
    public const DEVICE_RM2 = 'RM2';
    public const DEVICE_RMM = 'RM Mini';
    public const DEVICE_RMPP = 'RM Pro Phicomm';
    public const DEVICE_RM2HP = 'RM2 Home Plus';
    public const DEVICE_RM2PP = 'RM2 Pro Plus';
    public const DEVICE_RM2PP2 = 'RM2 Pro Plus2';
    public const DEVICE_RM2PPBL = 'RM2 Pro Plus BL';
    public const DEVICE_RMMS = 'RM Mini Shate';
    public const DEVICE_RM3PP = 'RM3 Pro Plus';
    public const DEVICE_A1 = 'A1';
    public const DEVICE_MP1 = 'MP1';
    public const DEVICE_S1AK = 'S1 (SmartOne Alarm Kit)';

    public const KNOWN_DEVICES = [
        0 => self::DEVICE_SP1,
        0x2711 => self::DEVICE_SP2,
        0x2719 => self::DEVICE_HSP2,
        0x7919 => self::DEVICE_HSP2,
        0x271a => self::DEVICE_HSP2,
        0x791a => self::DEVICE_HSP2,
        0x2720 => self::DEVICE_SPM,
        0x753e => self::DEVICE_SP3,
        0x2728 => self::DEVICE_SPM2,
        0x2733 => self::DEVICE_OEM_SPM,
        0x273e => self::DEVICE_OEM_SPM,
        0x7530 => self::DEVICE_OEM_SPM2,
        0x7918 => self::DEVICE_OEM_SPM2,
        0x2736 => self::DEVICE_SPMP,
        0x2712 => self::DEVICE_RM2,
        0x2737 => self::DEVICE_RMM,
        0x273d => self::DEVICE_RMPP,
        0x2783 => self::DEVICE_RM2HP,
        0x277c => self::DEVICE_RM2HP,
        0x272a => self::DEVICE_RM2PP,
        0x2787 => self::DEVICE_RM2PP2,
        0x278b => self::DEVICE_RM2PPBL,
        0x278f => self::DEVICE_RMMS,
        0x279d => self::DEVICE_RM3PP,
        0x2714 => self::DEVICE_A1,
        0x4EB5 => self::DEVICE_MP1,
        0x4EB7 => self::DEVICE_MP1,
        0x2722 => self::DEVICE_S1AK,
    ];

    /**
     * @var DeviceInterface
     */
    private $device;

    /**
     * @var int
     */
    private $deviceId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $model;

    public function __construct(DeviceInterface $device, int $deviceId, string $name)
    {
        $this->device = $device;
        $this->deviceId = $deviceId;
        $this->name = $name;
        $this->model = $this->getModelByDeviceId($deviceId);
    }

    public function getId(): int
    {
        return $this->deviceId;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMac(): string
    {
        return $this->device->getMac();
    }

    public function getIP(): string
    {
        return $this->device->getIP();
    }

    public function getPort(): int
    {
        return $this->device->getPort();
    }

    public function getCipher(): CipherInterface
    {
        return $this->device->getCipher();
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'ip' => $this->getIP(),
            'mac' => $this->getMac(),
            'id' => $this->getId(),
            'model' => $this->getModel()
        ];
    }

    private function getModelByDeviceId($deviceId)
    {
        return self::KNOWN_DEVICES[$deviceId] ?? self::DEVICE_UNKNOWN;
    }

    private function getDeviceClass()
    {
        return AuthenticatedDevice::class;
    }

    public function authenticate()
    {
        return Broadlink::authenticate($this, $this->getDeviceClass());
    }
}
