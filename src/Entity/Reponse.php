<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    private $question;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reponse;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $reponse_expected;

    #[ORM\OneToMany(mappedBy: 'reponse', targetEntity: History::class)]
    private $history_reponse;

    public function __construct()
    {
        $this->history_reponse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getReponseExpected(): ?int
    {
        return $this->reponse_expected;
    }

    public function setReponseExpected(?int $reponse_expected): self
    {
        $this->reponse_expected = $reponse_expected;

        return $this;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistoryReponse(): Collection
    {
        return $this->history_reponse;
    }

    public function addHistoryReponse(History $historyReponse): self
    {
        if (!$this->history_reponse->contains($historyReponse)) {
            $this->history_reponse[] = $historyReponse;
            $historyReponse->setReponse($this);
        }

        return $this;
    }

    public function removeHistoryReponse(History $historyReponse): self
    {
        if ($this->history_reponse->removeElement($historyReponse)) {
            // set the owning side to null (unless already changed)
            if ($historyReponse->getReponse() === $this) {
                $historyReponse->setReponse(null);
            }
        }

        return $this;
    }
}
