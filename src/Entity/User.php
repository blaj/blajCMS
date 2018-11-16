<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AssertCustom;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="Nazwa użytkownika jest zajęta!")
 * @UniqueEntity(fields="email", message="E-Mail jest zajęty!")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(
     *  min = 4,
     *  max = 18,
     *  minMessage = "Nazwa użytkownika musi być dłuższa niż {{ limit }} znaki!",
     *  maxMessage = "Nazwa użytkownika nie może być dłuższa niż {{ limit }} znaków!"
     * )
     * @Assert\Regex(
     *  pattern     = "/^[a-z0-9]+$/i",
     *  htmlPattern = "^[a-zA-Z0-9]+$",
     *  message="Nazwa użytkownika może zawierać tylko litery i cyfry!"
     * )
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min = 6,
     *  max = 4096,
     *  minMessage = "Hasło musi być dłuższe niż {{ limit }} znaków!",
     *  maxMessage = "Hasło nie może być dłuższe {{ limit }} znaków!"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user", orphanRemoval=true)
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(
     *  message = "E-Mail: '{{ value }}' jest niepoprawny!",
     *  checkMX = true
     * )
     * @AssertCustom\UniqueEmail()
     */
    private $plainEmail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="integer")
     */
    private $reputation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentReputation", mappedBy="user", orphanRemoval=true)
     */
    private $commentReputations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MessageTopic", mappedBy="fromUser", orphanRemoval=true)
     */
    private $messageTopicsFrom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MessageTopic", mappedBy="toUser", orphanRemoval=true)
     */
    private $messageTopicsTo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="user", orphanRemoval=true)
     */
    private $notifications;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->commentReputations = new ArrayCollection();
        $this->messageTopicsFrom = new ArrayCollection();
        $this->messageTopicsTo = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return (string) $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
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

    public function getPlainEmail(): ?string
    {
        return $this->plainEmail;
    }

    public function setPlainEmail(string $plainEmail): self
    {
        $this->plainEmail = $plainEmail;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getReputation(): ?int
    {
        return $this->reputation;
    }

    public function setReputation(int $reputation): self
    {
        $this->reputation = $reputation;

        return $this;
    }

    /**
     * @return Collection|CommentReputation[]
     */
    public function getCommentReputations(): Collection
    {
        return $this->commentReputations;
    }

    public function addCommentReputation(CommentReputation $commentReputation): self
    {
        if (!$this->commentReputations->contains($commentReputation)) {
            $this->commentReputations[] = $commentReputation;
            $commentReputation->setUser($this);
        }

        return $this;
    }

    public function removeCommentReputation(CommentReputation $commentReputation): self
    {
        if ($this->commentReputations->contains($commentReputation)) {
            $this->commentReputations->removeElement($commentReputation);
            // set the owning side to null (unless already changed)
            if ($commentReputation->getUser() === $this) {
                $commentReputation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MessageTopic[]
     */
    public function getMessageTopicsFrom(): Collection
    {
        return $this->messageTopicsFrom;
    }

    public function addMessageTopicsFrom(MessageTopic $messageTopicsFrom): self
    {
        if (!$this->messageTopicsFrom->contains($messageTopicsFrom)) {
            $this->messageTopicsFrom[] = $messageTopicsFrom;
            $messageTopicsFrom->setFromUser($this);
        }

        return $this;
    }

    public function removeMessageTopicsFrom(MessageTopic $messageTopicsFrom): self
    {
        if ($this->messageTopicsFrom->contains($messageTopicsFrom)) {
            $this->messageTopicsFrom->removeElement($messageTopicsFrom);
            // set the owning side to null (unless already changed)
            if ($messageTopicsFrom->getFromUser() === $this) {
                $messageTopicsFrom->setFromUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MessageTopic[]
     */
    public function getMessageTopicsTo(): Collection
    {
        return $this->messageTopicsTo;
    }

    public function addMessageTopicsTo(MessageTopic $messageTopicsTo): self
    {
        if (!$this->messageTopicsTo->contains($messageTopicsTo)) {
            $this->messageTopicsTo[] = $messageTopicsTo;
            $messageTopicsTo->setToUser($this);
        }

        return $this;
    }

    public function removeMessageTopicsTo(MessageTopic $messageTopicsTo): self
    {
        if ($this->messageTopicsTo->contains($messageTopicsTo)) {
            $this->messageTopicsTo->removeElement($messageTopicsTo);
            // set the owning side to null (unless already changed)
            if ($messageTopicsTo->getToUser() === $this) {
                $messageTopicsTo->setToUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }
}
