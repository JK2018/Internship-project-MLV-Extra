<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifeCycleCallbacks()
 */
class Booking 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention, la date doit être au bon format !")
     * @Assert\GreaterThanOrEqual("today", message="La date de debut est invalide!", groups={"front"})
     */
    private $startDate;



    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention, la date doit être au bon format !")
     * @Assert\GreaterThanOrEqual(propertyPath="startDate", message="La date de fin doit être après la date de début !")  
     */
    private $endDate;

    
      


    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Callback called for each new booking
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function prePersist(){
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTime();
        }
        if(empty($this->amount)){
            //hoursPerDay * nbr of days worked
            $this->amount = $this->ad->getHoursPerDay() * ($this->getDuration());
        }
        
    }

    public function getDuration(){
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days+1;
    }



    public function isBookableDates(){
        //connaitre les dates impossible pr l'annonce
        $notAvailableDays = $this->ad->getNotAvailableDays();
        //comparer avec les dates choisies
        $bookingDays = $this->getDays();

        //switch both timestamp arrays to date string format for comparaison
        $days = array_map(function($day){
            return $day->format('Y-m-d');
        }, $bookingDays);

        $notAvailable = array_map(function($day){
            return $day->format('Y-m-d');
        }, $notAvailableDays);

        foreach($days as $day){
            if(array_search($day, $notAvailable) !== false) return false;
        }

        return true;
    }

    /**
     * retreives an array of the days that corresponds to my booking
     *
     * @return array An array of DateTime objects representing the days of the booking
     */
    public function getDays(){
        $result = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            60*60*24
        );
        $days = array_map(function($dayTimestamp){
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $result);
        return $days;
    }
}
