<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CreateMatchesEvent extends Event
{
    private $leagueId;
    private $round;

    public function __construct(string $leagueId, int $round)
    {
        $this->leagueId = $leagueId;
        $this->round = $round;
    }

    public function getLeagueId(): string
    {
        return $this->leagueId;
    }

    public function getRound(): int
    {
        return $this->round;
    }
}
