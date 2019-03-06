<?php

namespace BroadlinkApi\Service;

use BroadlinkApi\Device\Authenticatable\Rm\RMDeviceSensor;
use BroadlinkApi\Device\Authenticatable\Rm\RMDevice;
use BroadlinkApi\Device\Authenticatable\Sp\SP2Device;
use BroadlinkApi\Device\Authenticatable\Sp\SPMiniOEM;
use BroadlinkApi\Device\IdentifiedDeviceInterface;
use BroadlinkApi\Device\UnknownIdentifiedDevice;

class DeviceFactory
{
    public const DEVICE_UNKNOWN = 'Unknown';
    public const DEVICE_SP1 = 'SP1';
    public const DEVICE_SP2 = 'SP2';
    public const DEVICE_HSP2 = 'Honeywell SP2';
    public const DEVICE_SPM = 'SPMini';
    public const DEVICE_OEM_SP3 = 'SP3 (OEM)';
    public const DEVICE_SP3 = 'SP3';
    public const DEVICE_SP3S = 'SP3S';
    public const DEVICE_SPM2 = 'SPMini2';
    public const DEVICE_OEM_SPM = 'SPMini (OEM)';
    public const DEVICE_OEM_SPM2 = 'SPMini2 (OEM)';
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
        0x7D00 => self::DEVICE_OEM_SP3,
        0x753e => self::DEVICE_SP3,
        0x947a => self::DEVICE_SP3S,
        0x9479 => self::DEVICE_SP3S,
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

    private const DEVICES_CLASS_MAP = [
        SP2Device::class => [
            0x2711,
            0x2719,
            0x7919,
            0x271a,
            0x791a,
            0x2720,
            0x753e,
            0x7D00,
            0x947a,
            0x9479,
            0x2728,
            0x273e,
            0x7530,
            0x7918,
            0x2736,
        ],
        SPMiniOEM::class => [
            0x2733,
        ],
        RMDevice::class => [
            0x279d,
        ],
        RMDeviceSensor::class => [
            0x2712,
            0x2737,
            0x273d,
            0x2783,
            0x277c,
            0x272a,
            0x2787,
            0x278b,
            0x278f,
        ]
    ];

    public function create(string $ip, string $mac, int $deviceId, string $name): IdentifiedDeviceInterface
    {
        $class = $this->getDeviceClass($deviceId);

        /** @var IdentifiedDeviceInterface $device */
        $device = new $class($ip, $mac, $deviceId, $name, $this->getModelByDeviceId($deviceId));
        $device->init();

        return $device;
    }

    private function getDeviceClass(int $deviceId): ?string
    {
        foreach (self::DEVICES_CLASS_MAP as $class => $mapping) {
            if (in_array($deviceId, $mapping)) {
                return $class;
            }
        }

        return UnknownIdentifiedDevice::class;
    }

    private function getModelByDeviceId($deviceId)
    {
        return self::KNOWN_DEVICES[$deviceId] ?? self::DEVICE_UNKNOWN;
    }
}
