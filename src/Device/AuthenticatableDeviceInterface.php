<?php

namespace BroadlinkApi\Device;

interface AuthenticatableDeviceInterface extends NetDeviceInterface
{
    public function getSessionId(): ?int;

    public function isAuthenticated(): bool;

    public function authenticate(): bool;
}
