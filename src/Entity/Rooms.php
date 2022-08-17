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
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="rooms")
     */
    private $user;

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
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favorites")
     */
    private $favoriteUsers;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->userAttributes = new ArrayCollection();
        $this->prototypeUsers = new ArrayCollection();
        $this->favoriteUsers = new ArrayCollection();
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
}
