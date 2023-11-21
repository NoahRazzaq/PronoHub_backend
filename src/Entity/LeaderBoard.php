<?php

namespace App\Entity;

use App\Repository\LeaderBoardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeaderBoardRepository::class)]
class LeaderBoard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $position = null;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    #[ORM\Column]
    private ?int $nbWin = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbLose = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getNbWin(): ?int
    {
        return $this->nbWin;
    }

    public function setNbWin(int $nbWin): static
    {
        $this->nbWin = $nbWin;

        return $this;
    }

    public function getNbLose(): ?int
    {
        return $this->nbLose;
    }

    public function setNbLose(?int $nbLose): static
    {
        $this->nbLose = $nbLose;

        return $this;
    }
}
