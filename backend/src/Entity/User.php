<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Post;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', columns: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["user", "post"])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180)]
    #[Groups(["user", "post"])]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    #[Groups(["user"])]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["user"])]
    private bool $isVerified = false;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: "author", orphanRemoval: true)]
    #[Groups(["user"])]
    private Collection $posts;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["user", "post"])]
    private ?string $username = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["user"])]
    private ?string $city = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["user"])]
    private ?string $country = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["user", "post"])]
    private ?string $profilePicture = null;

    /**
     * @var Collection<int, Follow>
     */
    #[ORM\OneToMany(mappedBy: 'follower', targetEntity: Follow::class)]
    private Collection $following;

    #[ORM\OneToMany(mappedBy: 'following', targetEntity: Follow::class)]
    private Collection $followers;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->followers = new ArrayCollection();
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Follow[]
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    public function addFollowing(Follow $follow): self
    {
        if (!$this->following->contains($follow)) {
            $this->following[] = $follow;
            $follow->setFollower($this);
        }

        return $this;
    }

    public function removeFollowing(Follow $follow): self
    {
        if ($this->following->removeElement($follow)) {
            // set the owning side to null (unless already changed)
            if ($follow->getFollower() === $this) {
                $follow->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Follow[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Follow $follow): self
    {
        if (!$this->followers->contains($follow)) {
            $this->followers[] = $follow;
            $follow->setFollowing($this);
        }

        return $this;
    }

    public function removeFollower(Follow $follow): self
    {
        if ($this->followers->removeElement($follow)) {
            // set the owning side to null (unless already changed)
            if ($follow->getFollowing() === $this) {
                $follow->setFollowing(null);
            }
        }

        return $this;
    }
}
