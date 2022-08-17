<?php

namespace App\Entity;

use App\Repository\RoomsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @ORM\Entity(repositoryClass=RoomsRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Rooms
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $enddate;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="rooms")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $uid;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="roomModerator")
     * @ORM\JoinColumn(nullable=true)
     */
    private $moderator;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="protoypeRooms")
     * @ORM\JoinTable(name="prototype_users")
     */
    private $prototypeUsers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $timeZone;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startUtc;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDateUtc;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favorites")
     */
    private $favoriteUsers;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $lobby;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $startTimestamp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $endTimestamp;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $hostUrl;


    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->userAttributes = new ArrayCollection();
        $this->prototypeUsers = new ArrayCollection();
        $this->favoriteUsers = new ArrayCollection();
    }

    public function setModerator(?User $moderator): self
    {
        $this->moderator = $moderator;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getPrototypeUsers(): Collection
    {
        return $this->prototypeUsers;
    }

    public function addPrototypeUser(User $prototypeUser): self
    {
        if (!$this->prototypeUsers->contains($prototypeUser)) {
            $this->prototypeUsers[] = $prototypeUser;
        }

        return $this;
    }

    public function removePrototypeUser(User $prototypeUser): self
    {
        $this->prototypeUsers->removeElement($prototypeUser);

        return $this;
    }

    public function getStartwithTimeZone(?User $user): ?\DateTimeInterface
    {
        if ($this->timeZone && $user && $user->getTimeZone()) {
            $data = new \DateTime($this->start->format('Y-m-d H:i:s'), new \DateTimeZone($this->timeZone));
            $laTimezone = new \DateTimeZone($user->getTimeZone());
            $data->setTimezone($laTimezone);
            return $data;
        } else {
            return $this->start;
        }
    }

    public function getEndwithTimeZone(?User $user): ?\DateTimeInterface
    {
        if ($this->timeZone && $user && $user->getTimeZone()) {
            $data = new \DateTime($this->enddate->format('Y-m-d H:i:s'), new \DateTimeZone($this->timeZone));
            $laTimezone = new \DateTimeZone($user->getTimeZone());
            $data->setTimezone($laTimezone);
            return $data;
        } else {
            return $this->enddate;
        }
    }

    public function getStartUtc(): ?\DateTimeInterface
    {
        return new \DateTime($this->startUtc->format('Y-m-d H:i:s'), new \DateTimeZone('utc'));
    }

    public function setStartUtc(?\DateTimeInterface $startUtc): self
    {
        $this->startUtc = $startUtc;

        return $this;
    }

    public function getEndDateUtc(): ?\DateTimeInterface
    {
        return new \DateTime($this->endDateUtc->format('Y-m-d H:i:s'), new \DateTimeZone('utc'));
    }

    public function setEndDateUtc(?\DateTimeInterface $endDateUtc): self
    {
        $this->endDateUtc = $endDateUtc;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavoriteUsers(): Collection
    {
        return $this->favoriteUsers;
    }

    public function addFavoriteUser(User $favoriteUser): self
    {
        if (!$this->favoriteUsers->contains($favoriteUser)) {
            $this->favoriteUsers[] = $favoriteUser;
            $favoriteUser->addFavorite($this);
        }

        return $this;
    }

    public function removeFavoriteUser(User $favoriteUser): self
    {
        if ($this->favoriteUsers->removeElement($favoriteUser)) {
            $favoriteUser->removeFavorite($this);
        }

        return $this;
    }

    public function getLobby(): ?bool
    {
        return $this->lobby;
    }

    public function setLobby(?bool $lobby): self
    {
        $this->lobby = $lobby;

        return $this;
    }

    public function getStartTimestamp(): ?int
    {
        return $this->startTimestamp;
    }

    public function setStartTimestamp(?int $startTimestamp): self
    {
        $this->startTimestamp = $startTimestamp;

        return $this;
    }

    public function getEndTimestamp(): ?int
    {
        return $this->endTimestamp;
    }

    public function setEndTimestamp(?int $endTimestamp): self
    {
        $this->endTimestamp = $endTimestamp;

        return $this;
    }

    public function getHostUrl(): ?string
    {
        return $this->hostUrl;
    }

    public function setHostUrl(?string $hostUrl): self
    {
        $this->hostUrl = $hostUrl;

        return $this;
    }
}
