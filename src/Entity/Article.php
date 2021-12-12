<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="ascii_string")
     * @Assert\Length(
     * min = 5,
     * max=10,
     * minMessage = "Le nom d'un article doit comporter au moin 5 caractères !",
     * minMessage = "Le nom d'un article doit comporter au plus 10 caractères !",
     * )
     */
    private $Nom;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\NotEqualTo(
     * value=0,
     * message = "Le prix d'un article ne doit pas etre vide !"
     * )
     */
    private $Prix;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->Nom;
    }

    public function setNom($Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->Prix;
    }

    public function setPrix(string $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
