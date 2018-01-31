<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CarPhoto
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\CarPhotoRepository")
 */
class CarPhoto extends Content
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Car", cascade={"persist"}, inversedBy="photos")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $car;

    /**
     * @ORM\ManyToOne(targetEntity="CarAccidentHistory", cascade={"persist"}, inversedBy="photos")
     * @ORM\JoinColumn(name="car_accident_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $carAccident;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $path;

    /**
     * @Assert\Image(
     *     maxSize = "5M"
     * )
     */
    protected $fileUpload;

    /**
     * @return mixed
     */
    public function getFileUpload()
    {
        return $this->fileUpload;
    }

    /**
     * @param mixed $fileUpload
     */
    public function setFileUpload($fileUpload)
    {
        $this->fileUpload = $fileUpload;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return CarPhoto
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
     * Set path.
     *
     * @param string|null $path
     *
     * @return CarPhoto
     */
    public function setPath($path = null)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set car.
     *
     * @param \App\AdminBundle\Entity\Car|null $car
     *
     * @return CarPhoto
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
     * Set carAccident.
     *
     * @param \App\AdminBundle\Entity\CarAccidentHistory|null $carAccident
     *
     * @return CarPhoto
     */
    public function setCarAccident(\App\AdminBundle\Entity\CarAccidentHistory $carAccident = null)
    {
        $this->carAccident = $carAccident;

        return $this;
    }

    /**
     * Get carAccident.
     *
     * @return \App\AdminBundle\Entity\CarAccidentHistory|null
     */
    public function getCarAccident()
    {
        return $this->carAccident;
    }
}
