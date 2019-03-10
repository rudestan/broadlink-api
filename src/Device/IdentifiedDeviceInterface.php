<?php

namespace BroadlinkApi\Device;

interface IdentifiedDeviceInterface extends NetDeviceInterface
{
    public const TYPE_RM = 'RM';

    public const TYPE_SP = 'SP';

    public const TYPE_SC1 = 'SC1';

    public const TYPE_UNKNOWN = 'Unknown';

    public function getType(): string;

    public function isAuthenticatable(): bool;

    public function postDiscovery(): void;
}
