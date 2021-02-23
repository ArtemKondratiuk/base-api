<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("comment")
     */
    private $id;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("post_datail")
     */
    private $post;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="comment")
     * @Assert\Length(
     *     min=5,
     *     minMessage="comment is short",
     *     max=10000,
     *     maxMessage="comment is long"
     * )
     * @Groups("comment")
     */
    private $massage;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups("comment")
     *
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMassage(): ?string
    {
        return $this->massage;
    }

    public function setMassage(string $massage): void
    {
        $this->massage = $massage;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

}
