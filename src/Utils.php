<?php

namespace BroadlinkApi;

class Utils
{
    public static function array2string(array $array): string
    {
        return (string) implode(array_map('\chr', $array));
    }

    public static function getIPAddressArray(string $ipAddress): array
    {
        $ipAddressArray = explode('.',$ipAddress);

        foreach ($ipAddressArray as &$i) {
            $i = (int) $i;
        }

        return $ipAddressArray;
    }

    public static function getMacAddressArray(string $macAddress): array
    {
        $macAddressArray = explode(':', $macAddress);

        foreach ($macAddressArray as &$m) {
            $m = hexdec($m);
        }

        return $macAddressArray;
    }

    public static function getLocalIp(): string
    {
        $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        socket_connect($s ,'8.8.8.8', 53);
        socket_getsockname($s, $localIp);
        socket_close($s);

        return $localIp;
    }
}
