<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use App\Validator\PostalCodeConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ApiResource(normalizationContext:[
    'groups' => ['user']
])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups('user')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('user')]
    private ?string $address = null;

    #[ORM\Column(length: 6, nullable: true)]
    #[Groups('user')]
    private ?string $postal_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('user')]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('user')]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('user')]
    private ?string $lastname = null;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: UserOpenHours::class)]
    private Collection $userOpenHours;

    #[ORM\OneToMany(mappedBy: 'user_has_booked', targetEntity: UserOpenHours::class)]
    private Collection $userOpenHoursBooked;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'user')]
    private Collection $categories;

    public function __construct()
    {
        $this->userOpenHours = new ArrayCollection();
        $this->userOpenHoursBooked = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, UserOpenHours>
     */
    public function getUserOpenHours(): Collection
    {
        return $this->userOpenHours;
    }

    public function addUserOpenHour(UserOpenHours $userOpenHour): self
    {
        if (!$this->userOpenHours->contains($userOpenHour)) {
            $this->userOpenHours->add($userOpenHour);
            $userOpenHour->setUserId($this);
        }

        return $this;
    }

    public function removeUserOpenHour(UserOpenHours $userOpenHour): self
    {
        if ($this->userOpenHours->removeElement($userOpenHour)) {
            // set the owning side to null (unless already changed)
            if ($userOpenHour->getUserId() === $this) {
                $userOpenHour->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserOpenHours>
     */
    public function getUserOpenHoursBooked(): Collection
    {
        return $this->userOpenHoursBooked;
    }

    public function addUserOpenHoursBooked(UserOpenHours $userOpenHoursBooked): self
    {
        if (!$this->userOpenHoursBooked->contains($userOpenHoursBooked)) {
            $this->userOpenHoursBooked->add($userOpenHoursBooked);
            $userOpenHoursBooked->setUserHasBooked($this);
        }

        return $this;
    }

    public function removeUserOpenHoursBooked(UserOpenHours $userOpenHoursBooked): self
    {
        if ($this->userOpenHoursBooked->removeElement($userOpenHoursBooked)) {
            // set the owning side to null (unless already changed)
            if ($userOpenHoursBooked->getUserHasBooked() === $this) {
                $userOpenHoursBooked->setUserHasBooked(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addUser($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeUser($this);
        }

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('postal_code', new NotBlank());
        $metadata->addPropertyConstraint('postal_code', new PostalCodeConstraint());
    }
}
