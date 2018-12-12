<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert; //in order to put constraints on feilds and make sure they are valid.

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 10, max=255, minMessage="10 caractères minimum!", maxMessage="255 caractères maxi!")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(min=0, max=24, minMessage="Chiffre doit être compris entre 0 et 24!", maxMessage="Chiffre doit être compris entre 0 et 24!")
     */
    private $hoursPerDay; //private $price;////////////////////////////////

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     */
    private $introduction;

    /**
     * @ORM\Column(type="string", length=2550)
     * @Assert\Type("string")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0, max=365, minMessage="Chiffre doit être compris entre 0 et 365!", maxMessage="Chiffre doit être compris entre 0 et 365!")
     */
    private $daysPerMission;  //private $rooms; ////////////////////////////////////////

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="ad", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * this generates and sets the slug from the slugified title. It is verified before each persist and update.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */
    public function generateSlug(){
        $slugId = (string)$this->id;
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title.mt_rand(1,1000));//random, to allow duplicate titles and unique slugs.
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getHoursPerDay(): ?float
    {
        return $this->hoursPerDay;
    }

    public function setHoursPerDay(float $hoursPerDay): self
    {
        $this->hoursPerDay = $hoursPerDay;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
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

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }


    public function getDaysPerMission(): ?int
    {
        return $this->daysPerMission;
    }

    public function setDaysPerMission(int $daysPerMission): self
    {
        $this->daysPerMission = $daysPerMission;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }
}
