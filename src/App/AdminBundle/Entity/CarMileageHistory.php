<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Car
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\CarMileageHistoryRepository")
 */
class CarMileageHistory extends Content
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Car", cascade={"persist"}, inversedBy="mileagesHistory")
     * @ORM\JoinColumn(name="car_mileage_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $car;

    /**
     * @ORM\ManyToOne(targetEntity="CarAccidentHistory", cascade={"persist"}, inversedBy="mileagesHistory")
     * @ORM\JoinColumn(name="car_mileage_accident_history_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $accident;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $mileage;

    /**
     * Set mileage.
     *
     * @param int|null $mileage
     *
     * @return CarMileageHistory
     */
    public function setMileage($mileage = null)
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * Get mileage.
     *
     * @return int|null
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * Set car.
     *
     * @param \App\AdminBundle\Entity\Car|null $car
     *
     * @return CarMileageHistory
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
     * Set accident.
     *
     * @param \App\AdminBundle\Entity\CarAccidentHistory|null $accident
     *
     * @return CarMileageHistory
     */
    public function setAccident(\App\AdminBundle\Entity\CarAccidentHistory $accident = null)
    {
        $this->accident = $accident;

        return $this;
    }

    /**
     * Get accident.
     *
     * @return \App\AdminBundle\Entity\CarAccidentHistory|null
     */
    public function getAccident()
    {
        return $this->accident;
    }
}
