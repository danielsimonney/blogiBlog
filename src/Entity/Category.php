<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=BlogPost::class, inversedBy="categories")
     */
    private $blogpost_category;

    public function __construct()
    {
        $this->blogpost_category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getBlogpostCategory(): Collection
    {
        return $this->blogpost_category;
    }

    public function addBlogpostCategory(BlogPost $blogpostCategory): self
    {
        if (!$this->blogpost_category->contains($blogpostCategory)) {
            $this->blogpost_category[] = $blogpostCategory;
        }

        return $this;
    }

    public function removeBlogpostCategory(BlogPost $blogpostCategory): self
    {
        if ($this->blogpost_category->contains($blogpostCategory)) {
            $this->blogpost_category->removeElement($blogpostCategory);
        }

        return $this;
    }
}
