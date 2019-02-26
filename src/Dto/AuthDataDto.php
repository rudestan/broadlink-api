<?php

namespace BroadlinkApi\Dto;

class AuthDataDto
{
    /**
     * @var array
     */
    private $key;

    /**
     * @var int
     */
    private $sessionId;

    public function __construct(array $key, int $sessionId)
    {
        $this->key = $key;
        $this->sessionId = $sessionId;
    }

    public function getKey(): array
    {
        return $this->key;
    }

    public function getSessionId(): int
    {
        return $this->sessionId;
    }
}
