<?php

namespace BroadlinkApi;

use BroadlinkApi\Command\CommandInterface;
use BroadlinkApi\Command\EncryptedCommandInterface;
use BroadlinkApi\Command\RawCommandInterface;
use BroadlinkApi\Device\AuthenticatedDevice;
use BroadlinkApi\Packet\PacketBuilder;

class Protocol
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct()
    {
        $this->connection = new Connection();
    }

    public static function create()
    {
        return new self();
    }

    public function executeCommand(CommandInterface $command): \Generator
    {
        $device = $command->getDevice();
        $cipher = $device->getCipher();

        if ($command instanceof RawCommandInterface) {
            $rootPacketBuilder = new PacketBuilder($command->getPacket());

        } elseif ($command instanceof EncryptedCommandInterface) {
            $rootPacketBuilder = PacketBuilder::create(0x38);
            $rootPacketBuilder->writeInt16(0x28,$this->getPacketId());
            $rootPacketBuilder->writeBytes(0x2a,Utils::getMacAddressArray($device->getMac()));

            if($device instanceof AuthenticatedDevice){
                $rootPacketBuilder->writeInt32(0x30,$device->getSessionId());
            }

            $payload = $command->getPayload();
            $payloadPacketBuilder = new PacketBuilder($payload);
            $encryptedPayload = $cipher->encrypt($payload);
            $rootPacketBuilder->setPayloadChecksum($payloadPacketBuilder->calculateChecksum());
            $rootPacketBuilder->attachPayload($encryptedPayload);
        } else {
            throw new \Exception('Unknown handler '.get_class($command));
        }

        $rootPacketBuilder->setCommand($command->getCommandId());
        $rootPacketBuilder->writeChecksum();

        $receivedPackets = $this->connection->sendPacketToDeviceArray(
            $rootPacketBuilder->getPacket(),
            $command->getDevice()
        );

        foreach ($receivedPackets as $packet) {
            $responsePacketBuilder = new PacketBuilder($packet);

            if($responsePacketBuilder->hasError()) {
                throw new \Exception('Wrong response packet');
            }

            if($command instanceof EncryptedCommandInterface) {
                $encryptedPayload = $responsePacketBuilder->extractPayload();
                $packet = $cipher->decrypt($encryptedPayload);
            }

            yield $command->handleResponse($packet);
        }
    }

    private function getPacketId()
    {
        return random_int(0, 0xffff);
    }
}
