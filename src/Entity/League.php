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

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'league')]
    private Collection $users;

    #[ORM\ManyToOne(inversedBy: 'leagues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUserCreator = null;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addLeague($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeLeague($this);
        }

        return $this;
    }

    public function getIdUserCreator(): ?User
    {
        return $this->idUserCreator;
    }

    public function setIdUserCreator(?User $idUserCreator): static
    {
        $this->idUserCreator = $idUserCreator;

        return $this;
    }
}
