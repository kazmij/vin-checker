<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Car
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\CarAccidentHistoryRepository")
 */
class CarAccidentHistory extends Content
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Car", cascade={"persist"}, inversedBy="accidentsHistory")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $car;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $place;

    /**
     * @ORM\OneToMany(targetEntity="CarPhoto", mappedBy="carAccident", cascade={"persist", "remove"})
     */
    protected $photos;

    /**
     * @ORM\OneToMany(targetEntity="CarMileageHistory", mappedBy="accident", cascade={"persist", "remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $mileagesHistory;

    /**
     * @var string
     * @ORM\Column(type="datetime")
     */
    protected $accidentDate;

    /**
     * Set car.
     *
     * @param \App\AdminBundle\Entity\Car|null $car
     *
     * @return CarAccidentHistory
     */
    public function setCar(\App\AdminBundle\Entity\Car $car = null)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car.
     *
     * @return \App\AdminBundle\Entity\Car|null
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return CarAccidentHistory
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add photo.
     *
     * @param \App\AdminBundle\Entity\CarPhoto $photo
     *
     * @return CarAccidentHistory
     */
    public function addPhoto(\App\AdminBundle\Entity\CarPhoto $photo = null)
    {
        if($photo) {
            $this->photos[] = $photo;
            $photo->setCarAccident($this);
        }

        return $this;
    }

    /**
     * Remove photo.
     *
     * @param \App\AdminBundle\Entity\CarPhoto $photo
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePhoto(\App\AdminBundle\Entity\CarPhoto $photo)
    {
        return $this->photos->removeElement($photo);
    }

    /**
     * Get photos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Add mileagesHistory.
     *
     * @param \App\AdminBundle\Entity\CarMileageHistory $mileagesHistory
     *
     * @return CarAccidentHistory
     */
    public function addMileagesHistory(\App\AdminBundle\Entity\CarMileageHistory $mileagesHistory)
    {
        $this->mileagesHistory[] = $mileagesHistory;
        $mileagesHistory->setAccident($this);
        if($this->getCar()) {
            $this->getCar()->addMileagesHistory($mileagesHistory);
            $mileagesHistory->setCar($this->getCar());
        }

        return $this;
    }

    /**
     * Remove mileagesHistory.
     *
     * @param \App\AdminBundle\Entity\CarMileageHistory $mileagesHistory
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMileagesHistory(\App\AdminBundle\Entity\CarMileageHistory $mileagesHistory)
    {
        return $this->mileagesHistory->removeElement($mileagesHistory);
    }

    /**
     * Get mileagesHistory.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMileagesHistory()
    {
        return $this->mileagesHistory;
    }

    /**
     * Set place.
     *
     * @param string|null $place
     *
     * @return CarAccidentHistory
     */
    public function setPlace($place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place.
     *
     * @return string|null
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set accidentDate.
     *
     * @param \DateTime $accidentDate
     *
     * @return CarAccidentHistory
     */
    public function setAccidentDate($accidentDate)
    {
        $this->accidentDate = $accidentDate;

        return $this;
    }

    /**
     * Get accidentDate.
     *
     * @return \DateTime
     */
    public function getAccidentDate()
    {
        return $this->accidentDate;
    }
}
