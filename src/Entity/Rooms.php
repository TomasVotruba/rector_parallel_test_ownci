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
     * @ORM\Column(type="float")
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $sequence;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $uidReal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onlyRegisteredUsers = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $agenda;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dissallowScreenshareGlobal;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dissallowPrivateMessage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $public = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showRoomOnJoinpage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $uidParticipant;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $uidModerator;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxParticipants;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $scheduleMeeting;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $waitinglist;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $repeaterRemoved;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="protoypeRooms")
     * @ORM\JoinTable(name="prototype_users")
     */
    private $prototypeUsers;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $persistantRoom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $totalOpenRooms;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalOpenRoomsOpenTime = 30;

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

    /**
     * @ORM\PreFlush
     */
    public function preUpdate()
    {
        $timezone = $this->timeZone ? new \DateTimeZone($this->timeZone) : null;

        if ($this->start) {
            $dateStart = new \DateTime($this->start->format('Y-m-d H:i:s'), $timezone);
            $this->startUtc = $dateStart->setTimezone(new \DateTimeZone('utc'));
            $this->startTimestamp = $dateStart->getTimestamp();
        }
        if ($this->enddate) {
            $dateEnd = new \DateTime($this->enddate->format('Y-m-d H:i:s'), $timezone);
            $this->endDateUtc = $dateEnd->setTimezone(new \DateTimeZone('utc'));
            $this->endTimestamp = $dateEnd->getTimestamp();
        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }


    public function setStart(?\DateTimeInterface $start): self
    {
        $this->start = $start;
        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(?\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getUid(): ?string
    {
        return strtolower($this->uid);
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getModerator(): ?User
    {
        return $this->moderator;
    }

    public function setModerator(?User $moderator): self
    {
        $this->moderator = $moderator;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(int $sequence): self
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function getUidReal(): ?string
    {
        return $this->uidReal;
    }

    public function setUidReal(string $uidReal): self
    {
        $this->uidReal = $uidReal;

        return $this;
    }

    public function getOnlyRegisteredUsers(): ?bool
    {
        return $this->onlyRegisteredUsers;
    }

    public function setOnlyRegisteredUsers(bool $onlyRegisteredUsers): self
    {
        $this->onlyRegisteredUsers = $onlyRegisteredUsers;

        return $this;
    }

    public function getAgenda(): ?string
    {
        return $this->agenda;
    }

    public function setAgenda(?string $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
    }

    public function setPublic(?bool $public): self
    {
        $this->public = $public;

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

    public function getPersistantRoom(): ?bool
    {
        return $this->persistantRoom;
    }

    public function setPersistantRoom(?bool $persistantRoom): self
    {
        $this->persistantRoom = $persistantRoom;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTotalOpenRooms(): ?bool
    {
        return $this->totalOpenRooms;
    }

    public function setTotalOpenRooms(?bool $totalOpenRooms): self
    {
        $this->totalOpenRooms = $totalOpenRooms;

        return $this;
    }

    public function getTotalOpenRoomsOpenTime(): ?int
    {
        return $this->totalOpenRoomsOpenTime;
    }

    public function setTotalOpenRoomsOpenTime(?int $totalOpenRoomsOpenTime): self
    {
        $this->totalOpenRoomsOpenTime = $totalOpenRoomsOpenTime;

        return $this;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function setTimeZone(?string $timeZone): self
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function getTimeZoneAuto(): ?string
    {
        if ($this->timeZone) {
            return $this->timeZone;
        } else {
            return $this->moderator->getTimeZone();
        }
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
