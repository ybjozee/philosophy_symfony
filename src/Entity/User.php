<?php

namespace App\Entity;

use App\Utility\DateTimeUtility;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, Serializable
{
    public const ADMIN_ROLE = 'ROLE_ADMIN';
    public const USER_ROLE = 'ROLE_USER';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email(
     * message = "The email '{{ value }}' is not a valid email."
     * )
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column()
     */
    private $password;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdOn;
//
//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="author", orphanRemoval=true)
//     */
//    private $posts;
//
//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=true)
//     */
//    private $comments;


    public function __construct()
    {
        $this->createdOn = DateTimeUtility::getCurrentDateTime();
//        $this->posts = new ArrayCollection();
//        $this->comments = new ArrayCollection();
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
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::USER_ROLE;

        return array_unique($roles);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        [$this->id, $this->username, $this->password] =
            unserialize($serialized, ['allowed_classes' => false]);
    }

    public function isAdmin(): bool
    {
        return in_array(self::ADMIN_ROLE, $this->roles);
    }

    public function createFromArray(array $inputArray): self{
        foreach ($inputArray as $key => $value){
            $this->$key = $value;
        }
        return $this;
    }
//
//    /**
//     * @return Collection|Post[]
//     */
//    public function getPosts(): Collection
//    {
//        return $this->posts;
//    }
//
//    public function addPost(Post $post): self
//    {
//        if (!$this->posts->contains($post)) {
//            $this->posts[] = $post;
//            $post->setAuthor($this);
//        }
//
//        return $this;
//    }
//
//    public function removePost(Post $post): self
//    {
//        if ($this->posts->contains($post)) {
//            $this->posts->removeElement($post);
//            // set the owning side to null (unless already changed)
//            if ($post->getAuthor() === $this) {
//                $post->setAuthor(null);
//            }
//        }
//
//        return $this;
//    }
//
//    /**
//     * @return Collection|Comment[]
//     */
//    public function getComments(): Collection
//    {
//        return $this->comments;
//    }
//
//    public function addComment(Comment $comment): self
//    {
//        if (!$this->comments->contains($comment)) {
//            $this->comments[] = $comment;
//            $comment->setAuthor($this);
//        }
//
//        return $this;
//    }
//
//    public function removeComment(Comment $comment): self
//    {
//        if ($this->comments->contains($comment)) {
//            $this->comments->removeElement($comment);
//            // set the owning side to null (unless already changed)
//            if ($comment->getAuthor() === $this) {
//                $comment->setAuthor(null);
//            }
//        }
//
//        return $this;
//    }
}
