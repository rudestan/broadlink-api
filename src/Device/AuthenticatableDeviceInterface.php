<?php

namespace BroadlinkApi\Device;

interface AuthenticatableDeviceInterface extends IdentifiedDeviceInterface
{
    public function getSessionId(): ?int;

    public function isAuthenticated(): bool;

    public function authenticate(): bool;
}
