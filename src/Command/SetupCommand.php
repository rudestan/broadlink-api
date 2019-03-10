<?php

namespace BroadlinkApi\Command;

use BroadlinkApi\Device\NetDeviceInterface;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Exception\CommandException;
use BroadlinkApi\Utils;
use BroadlinkApi\Packet\PacketBuilder;

class SetupCommand implements RawCommandInterface
{
    public const SECURITY_NONE = 0;

    public const SECURITY_WEP = 1;

    public const SECURITY_WPA1 = 2;

    public const SECURITY_WPA2 = 3;

    public const SECURITY_WPA1_2 = 4;
    
    private const SUPPORTED_SECURITY_MODES = [
        self::SECURITY_NONE,
        self::SECURITY_WEP,
        self::SECURITY_WPA1,
        self::SECURITY_WPA2,
        self::SECURITY_WPA1_2,
    ];

    /**
     * @var NetDeviceInterface
     */
    private $device;

    /**
     * @var string
     */
    private $ssid;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $securityMode;

    /**
     * @throws CommandException
     */
    public function __construct(NetDeviceInterface $device, string $ssid, string $password, int $securityMode)
    {
        if (!$this->isSecurityModeSupported($securityMode)) {
            throw new CommandException('Security mode is not supported!');
        }

        $this->device = $device;
        $this->ssid = $ssid;
        $this->password = $password;
        $this->securityMode = $securityMode;
    }

    public function getCommandId(): int
    {
        return CommandInterface::COMMAND_SETUP;
    }

    public function getDevice(): NetDeviceInterface
    {
        return $this->device;
    }

    public function getPacket(): Packet
    {
        $packetBuilder = PacketBuilder::create(0x88);

        $packetBuilder->writeByte(0x26, 0x14);

        // Add ssid to payload
        $ssidStart = 68;
        $ssidLength = 0;


        foreach (str_split($this->ssid) as $char) {
            $packetBuilder->writeByte($ssidStart + $ssidLength, ord($char));
            $ssidLength++;
        }

        // Add password to payload
        $passStart = 100;
        $passLength = 0;

        foreach(str_split($this->password) as $char) {
            $packetBuilder->writeByte($passStart + $passLength, ord($char));
            $passLength++;
        }

        $packetBuilder->writeByte(0x84, $ssidLength);
        $packetBuilder->writeByte(0x85, $passLength);
        $packetBuilder->writeByte(0x86, $this->securityMode);

        $checksum = 0xbeaf;
        foreach($packetBuilder->getPacket()->toArray() as $item) {
            $checksum += $item;
            $checksum = $checksum & 0xffff;
        }

        $packetBuilder->writeByte(0x20, $checksum & 0xff);
        $packetBuilder->writeByte(0x21, $checksum >> 8);
        //$packetBuilder->writeChecksum();

/*
payload = bytearray(0x88)
  payload[0x26] = 0x14  # This seems to always be set to 14
  # Add the SSID to the payload
  ssid_start = 68
  ssid_length = 0
  for letter in ssid:
    payload[(ssid_start + ssid_length)] = ord(letter)
    ssid_length += 1
  # Add the WiFi password to the payload
  pass_start = 100
  pass_length = 0
  for letter in password:
    payload[(pass_start + pass_length)] = ord(letter)
    pass_length += 1

  payload[0x84] = ssid_length  # Character length of SSID
  payload[0x85] = pass_length  # Character length of password
  payload[0x86] = security_mode  # Type of encryption (00 - none, 01 = WEP, 02 = WPA1, 03 = WPA2, 04 = WPA1/2)

  checksum = 0xbeaf
  for i in range(len(payload)):
    checksum += payload[i]
    checksum = checksum & 0xffff

  payload[0x20] = checksum & 0xff  # Checksum 1 position
  payload[0x21] = checksum >> 8  # Checksum 2 position

 */



        return $packetBuilder->getPacket();
    }

    private function isSecurityModeSupported(string $securityMode): bool
    {
        return in_array($securityMode, self::SUPPORTED_SECURITY_MODES);
    }

    public function handleResponse(Packet $packet)
    {
        dump($packet);die();
    }
}