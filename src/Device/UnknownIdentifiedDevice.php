<?php

namespace BroadlinkApi\Device;

class UnknownIdentifiedDevice extends AbstractIdentifiedDevice
{
    public function getType(): string
    {
        return self::TYPE_UNKNOWN;
    }

    public function isAuthenticatable(): bool
    {
        return false;
    }
}
