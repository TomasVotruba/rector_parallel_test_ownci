<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Service\FormatName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable()
 */
class User
{
    private FormatName $formatName;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank(message="fos_user.password.blank", groups={"Registration", "ResetPassword", "ChangePassword"})
     * @Assert\Length(min=8,
     *     minMessage="fos_user.password.short",
     *     groups={"Registration", "Profile", "ResetPassword", "ChangePassword"})
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="text")
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $keycloakId;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $registerId;

    /**
     * @ORM\ManyToMany(targetEntity=Rooms::class, mappedBy="user")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity=Rooms::class, mappedBy="moderator")
     */
    private $roomModerator;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->roomModerator = new ArrayCollection();
        $this->addressbook = new ArrayCollection();
        $this->addressbookInverse = new ArrayCollection();
        $this->roomsAttributes = new ArrayCollection();
        $this->protoypeRooms = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    /**
     * @return Collection|Rooms[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Rooms $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->addUser($this);
        }

        return $this;
    }

    public function removeRoom(Rooms $room): self
    {
        if ($this->rooms->removeElement($room)) {
            $room->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Rooms[]
     */
    public function getRoomModerator(): Collection
    {
        return $this->roomModerator;
    }

    public function addRoomModerator(Rooms $roomModerator): self
    {
        if (!$this->roomModerator->contains($roomModerator)) {
            $this->roomModerator[] = $roomModerator;
            $roomModerator->setModerator($this);
        }

        return $this;
    }

}
