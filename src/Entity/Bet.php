<?php

namespace App\Entity;

use App\Repository\BetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BetRepository::class)]
class Bet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isDraw = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'bets')]
    private ?Game $game = null;

    #[ORM\ManyToOne(inversedBy: 'bets')]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'bets')]
    private ?League $league = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsDraw(): ?bool
    {
        return $this->isDraw;
    }

    public function setIsDraw(bool $isDraw): static
    {
        $this->isDraw = $isDraw;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): static
    {
        $this->league = $league;

        return $this;
    }
}
