<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CreateMatchesEvent extends Event
{
    private $leagueId;
    private $round;
    private $seasonYear;

    public function __construct( $leagueId,  $round, $seasonYear)
    {
        $this->leagueId = $leagueId;
        $this->round = $round;
        $this->seasonYear = $seasonYear;
    }

    public function getLeagueId(): string
    {
        return $this->leagueId;
    }

    public function getRound(): int
    {
        return $this->round;
    }

    public function getSeasonYear(): string
    {
        return $this->seasonYear;
    }
}
