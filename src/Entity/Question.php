<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[UniqueEntity('question')]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    private $categorie;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $question;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: History::class)]
    private $history_question;

    public function __construct()
    {
        $this->history_question = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistoryQuestion(): Collection
    {
        return $this->history_question;
    }

    public function addHistoryQuestion(History $historyQuestion): self
    {
        if (!$this->history_question->contains($historyQuestion)) {
            $this->history_question[] = $historyQuestion;
            $historyQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeHistoryQuestion(History $historyQuestion): self
    {
        if ($this->history_question->removeElement($historyQuestion)) {
            // set the owning side to null (unless already changed)
            if ($historyQuestion->getQuestion() === $this) {
                $historyQuestion->setQuestion(null);
            }
        }

        return $this;
    }
}
