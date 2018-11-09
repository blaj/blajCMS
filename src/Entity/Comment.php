<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\Column(type="integer")
     */
    private $reputation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentReputation", mappedBy="comment", orphanRemoval=true)
     */
    private $commentReputations;

    public function __construct()
    {
        $this->commentReputations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

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
            $commentReputation->setComment($this);
        }

        return $this;
    }

    public function removeCommentReputation(CommentReputation $commentReputation): self
    {
        if ($this->commentReputations->contains($commentReputation)) {
            $this->commentReputations->removeElement($commentReputation);
            // set the owning side to null (unless already changed)
            if ($commentReputation->getComment() === $this) {
                $commentReputation->setComment(null);
            }
        }

        return $this;
    }
}
