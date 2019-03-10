<?php

namespace BroadlinkApi\Device;

use BroadlinkApi\Cipher\CipherInterface;
use BroadlinkApi\Cipher\Cipher;
use BroadlinkApi\Command\DiscoverCommand;
use BroadlinkApi\Command\SetupCommand;
use BroadlinkApi\Exception\CommandException;
use BroadlinkApi\Exception\ProtocolException;
use BroadlinkApi\Protocol;

final class NetDevice implements NetDeviceInterface
{
    /**
     * @var Protocol
     */
    private $protocol;

    /**
     * @var CipherInterface
     */
    private $cipher;

    public function __construct()
    {
        $this->protocol = new Protocol();
        $this->cipher = new Cipher(self::BASE_KEY, self::BASE_IV);
    }

    public static function create()
    {
        return new self();
    }

    public function getIp(): string
    {
        return self::DEFAULT_IP;
    }

    public function getPort(): int
    {
        return self::DEFAULT_PORT;
    }

    public function getMac(): string
    {
        return '';
    }

    public function getCipher(): CipherInterface
    {
        return $this->cipher;
    }

    /**
     * @throws ProtocolException
     * @throws CommandException
     */
    public function discover(): array
    {
        $discoverCommand = new DiscoverCommand($this);
        $devices = [];

        foreach($this->protocol->executeCommand($discoverCommand) as $device){
            $devices[] = $device;
        }

        return $devices;
    }

    /**
     * @throws ProtocolException
     * @throws CommandException
     */
    public function setup(string $ssid, string $password, ?string $securityMode = SetupCommand::SECURITY_NONE): void
    {
        $setupCommand = new SetupCommand($this, $ssid, $password, $securityMode);

        $this->protocol->executeSetupCommand($setupCommand);
    }
}
