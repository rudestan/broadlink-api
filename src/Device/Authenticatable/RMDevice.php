<?php

namespace BroadlinkApi\Device\Authenticatable;

use BroadlinkApi\Command\Authenticated\CheckLearnedCommand;
use BroadlinkApi\Command\Authenticated\EnterLearningCommand;
use BroadlinkApi\Command\Authenticated\SendCommand;
use BroadlinkApi\Device\AbstractAuthenticatableDevice;
use BroadlinkApi\Packet\Packet;
use BroadlinkApi\Exception\ProtocolException;

class RMDevice extends AbstractAuthenticatableDevice
{
    /**
     * @throws ProtocolException
     */
    public function enterLearning()
    {
        $this->protocol->executeCommand(new EnterLearningCommand($this))->current();
    }

    public function getLearnedCommand(): ?Packet
    {
        try {
            $learnedCommand = $this->protocol->executeCommand(new CheckLearnedCommand($this))->current();
        } catch (ProtocolException $e) {
            return null;
        }

        return $learnedCommand;
    }

    /**
     * @throws ProtocolException
     */
    public function sendCommand(Packet $packet)
    {
        $this->protocol->executeCommand(new SendCommand($this, $packet))->current();
    }
}
