<?php

namespace BroadlinkApi\Packet;

class PacketBuilder
{
    private const COMMAND_ADDRESS = 0x26;

    private const ERROR_ADDRESS = 0x22;

    private const CHECKSUM_ADDRESS = 0x20;

    private const PAYLOAD_ADDRESS = 0x38;

    private const PAYLOAD_CHECKSUM_ADDRESS = 0x34;

    /**
     * @var Packet
     */
    private $packet;

    public function __construct(Packet $packet)
    {
        $this->packet = $packet;
    }

    public function writeByte(int $address, int $value): self
    {
        $value &= 0xff;
        $this->packet[$address] = $value;

        return $this;
    }

    public function writeBytes(int $startAddress, array $values): self
    {
        $values = array_reverse($values);
        $counter = 0;

        foreach ($values as $value) {
            $this->writeByte($startAddress+$counter, $value);

            $counter++;
        }

        return $this;
    }

    public function fillWithByte(array $addresses, int $byte): self
    {
        foreach ($addresses as $address) {
            $this->writeByte($address,$byte);
        }

        return $this;
    }

    public function writeInt16(int $address, int $value): self
    {
        $value &= 0xffff;
        $this->writeBytes($address,[$value >> 8, $value & 0xff]);

        return $this;
    }

    public function writeInt32(int $address,int $value): self
    {
        $value &= 0xffffffff;
        $this->writeBytes($address,[$value >> 24 & 0xff, $value >> 16 & 0xff,$value >> 8 & 0xff,$value & 0xff]);

        return $this;
    }

    public function readByte(int $address): int
    {
        return $this->packet[$address];
    }

    public function readBytes(int $startAddress, int $count = null): array
    {
        if($count === null) {
            $count = $this->packet->count() - $startAddress;
        }

        $bytes = [];

        for ($address = $startAddress + ($count-1); $address >= $startAddress; $address--) {
            $bytes[] = $this->readByte($address);
        }

        return $bytes;
    }

    public function readInt16(int $address): int
    {
        $bytes = $this->readBytes($address, 2);

        return hexdec(vsprintf('%x%x', $bytes));
    }

    public function readInt32(int $address): int
    {
        $bytes = $this->readBytes($address,4);

        return hexdec(vsprintf('%x%x%x%x', $bytes));
    }

    public function readFloat16(int $address): float
    {
        $bytes = $this->readBytes($address, 2);

        return $bytes[1] + ($bytes[0] / 10);
    }

    public function getChecksum(): int
    {
        return $this->readInt16(self::CHECKSUM_ADDRESS);
    }

    public function setChecksum(int $checksum): self
    {
        $this->writeInt16(self::CHECKSUM_ADDRESS, $checksum);

        return $this;
    }

    public function setPayloadChecksum(int $checksum): void
    {
        $this->writeInt16(self::PAYLOAD_CHECKSUM_ADDRESS, $checksum);
    }

    public function attachPayload(Packet $payload): self
    {
        $endAddress = static::PAYLOAD_ADDRESS + $payload->count();

        if($endAddress>$this->packet->count()) {
            $this->packet->setSize($endAddress);
        }

        $this->writeBytes(self::PAYLOAD_ADDRESS, array_reverse($payload->toArray()));

        return $this;
    }

    public function extractPayload(): Packet
    {
        $bytesArray = $this->readBytes(self::PAYLOAD_ADDRESS);

        return Packet::fromArray(array_reverse($bytesArray));
    }

    public function calculateChecksum()
    {
        $checksum = 0xbeaf;
        $checksum +=array_sum($this->packet->toArray());

        return $checksum;
    }

    public function writeChecksum(): self
    {
        $this->writeInt16(self::CHECKSUM_ADDRESS,0);
        $this->writeInt16(self::CHECKSUM_ADDRESS,$this->calculateChecksum());

        return $this;
    }

    public function isChecksumValid(): bool
    {
        $oldChecksum = $this->getChecksum();
        $this->writeInt16(self::CHECKSUM_ADDRESS, 0);

        $newChecksum = $this->calculateChecksum();
        $this->writeInt16(self::CHECKSUM_ADDRESS, $oldChecksum);

        return $oldChecksum === $newChecksum;
    }

    public function hasError(): bool
    {
        return $this->readInt16(self::ERROR_ADDRESS) !== 0;
    }

    public function getPacket():Packet
    {
        return $this->packet;
    }

    public function setCommand(int $command): self
    {
        return $this->writeByte(self::COMMAND_ADDRESS,$command);
    }

    public static function create(int $size): self
    {
        return new self(Packet::createZeroPacket($size));
    }
}
