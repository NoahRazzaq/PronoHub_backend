<?php

namespace App\Entity;

use App\Repository\LeagueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeagueRepository::class)]
class League
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $codeInvite = null;

    #[ORM\OneToMany(mappedBy: 'league', targetEntity: Bet::class)]
    private Collection $bets;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCodeInvite(): ?int
    {
        return $this->codeInvite;
    }

    public function setCodeInvite(int $codeInvite): static
    {
        $this->codeInvite = $codeInvite;

        return $this;
    }

    /**
     * @return Collection<int, Bet>
     */
    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function addBet(Bet $bet): static
    {
        if (!$this->bets->contains($bet)) {
            $this->bets->add($bet);
            $bet->setLeague($this);
        }

        return $this;
    }

    public function removeBet(Bet $bet): static
    {
        if ($this->bets->removeElement($bet)) {
            // set the owning side to null (unless already changed)
            if ($bet->getLeague() === $this) {
                $bet->setLeague(null);
            }
        }

        return $this;
    }
}
