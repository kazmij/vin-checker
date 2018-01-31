<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Car
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\CarRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Car extends Content
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $yearOfManufacture;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $color;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $mileage;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    protected $vin;

    /**
     * @ORM\PostLoad()
     */
    public function postLoad() {
        if($this->getManufacturer()) {
            $this->setManufacturerData($this->getManufacturer()->getId());
        }
        if($this->getModel()) {
            $this->setModelData($this->getModel()->getId());
        }
        if($this->getModelTrim()) {
            $this->setTrimData($this->getModelTrim()->getId());
        }
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @ORM\ManyToOne(targetEntity="ModelTrim", cascade={"persist"})
     * @ORM\JoinColumn(name="model_trim_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $modelTrim;

    /**
     * @ORM\ManyToOne(targetEntity="Manufacturer", cascade={"persist"})
     * @ORM\JoinColumn(name="model_manufacturer_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity="Model", cascade={"persist"})
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $model;

    protected $modelData;

    protected $manufacturerData;

    protected $trimData;

    /**
     * @return mixed
     */
    public function getModelData()
    {
        return $this->modelData;
    }

    /**
     * @param mixed $modelData
     */
    public function setModelData($modelData)
    {
        $this->modelData = $modelData;
    }

    /**
     * @return mixed
     */
    public function getManufacturerData()
    {
        return $this->manufacturerData;
    }

    /**
     * @param mixed $manufacturerData
     */
    public function setManufacturerData($manufacturerData)
    {
        $this->manufacturerData = $manufacturerData;
    }

    /**
     * @return mixed
     */
    public function getTrimData()
    {
        return $this->trimData;
    }

    /**
     * @param mixed $trimData
     */
    public function setTrimData($trimData)
    {
        $this->trimData = $trimData;
    }

    /**
     * @ORM\OneToMany(targetEntity="CarMileageHistory", mappedBy="car", cascade={"persist", "remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $mileagesHistory;

    /**
     * @ORM\OneToMany(targetEntity="CarAccidentHistory", mappedBy="car", cascade={"persist", "remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $accidentsHistory;

    /**
     * @ORM\OneToMany(targetEntity="CarPhoto", mappedBy="car", cascade={"persist", "remove"})
     */
    protected $photos;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * Set yearOfManufacture.
     *
     * @param int|null $yearOfManufacture
     *
     * @return Car
     */
    public function setYearOfManufacture($yearOfManufacture = null)
    {
        $this->yearOfManufacture = $yearOfManufacture;

        return $this;
    }

    /**
     * Get yearOfManufacture.
     *
     * @return int|null
     */
    public function getYearOfManufacture()
    {
        return $this->yearOfManufacture;
    }

    /**
     * Set color.
     *
     * @param string|null $color
     *
     * @return Car
     */
    public function setColor($color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set mileage.
     *
     * @param int|null $mileage
     *
     * @return Car
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
     * Set modelTrim.
     *
     * @param \App\AdminBundle\Entity\ModelTrim|null $modelTrim
     *
     * @return Car
     */
    public function setModelTrim(\App\AdminBundle\Entity\ModelTrim $modelTrim = null)
    {
        $this->modelTrim = $modelTrim;

        return $this;
    }

    /**
     * Get modelTrim.
     *
     * @return \App\AdminBundle\Entity\ModelTrim|null
     */
    public function getModelTrim()
    {
        return $this->modelTrim;
    }

    /**
     * Add mileagesHistory.
     *
     * @param \App\AdminBundle\Entity\CarMileageHistory $mileagesHistory
     *
     * @return Car
     */
    public function addMileagesHistory(\App\AdminBundle\Entity\CarMileageHistory $mileagesHistory)
    {
        $this->mileagesHistory[] = $mileagesHistory;
        $mileagesHistory->setCar($this);

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
     * Add accidentsHistory.
     *
     * @param \App\AdminBundle\Entity\CarAccidentHistory $accidentsHistory
     *
     * @return Car
     */
    public function addAccidentsHistory(\App\AdminBundle\Entity\CarAccidentHistory $accidentsHistory)
    {
        $this->accidentsHistory[] = $accidentsHistory;

        return $this;
    }

    /**
     * Remove accidentsHistory.
     *
     * @param \App\AdminBundle\Entity\CarAccidentHistory $accidentsHistory
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAccidentsHistory(\App\AdminBundle\Entity\CarAccidentHistory $accidentsHistory)
    {
        return $this->accidentsHistory->removeElement($accidentsHistory);
    }

    /**
     * Get accidentsHistory.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccidentsHistory()
    {
        return $this->accidentsHistory;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Car
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
     * @return Car
     */
    public function addPhoto(\App\AdminBundle\Entity\CarPhoto $photo)
    {
        $this->photos[] = $photo;
        $photo->setCar($this);

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
     * Set vin.
     *
     * @param string|null $vin
     *
     * @return Car
     */
    public function setVin($vin = null)
    {
        $this->vin = $vin;

        return $this;
    }

    /**
     * Get vin.
     *
     * @return string|null
     */
    public function getVin()
    {
        return $this->vin;
    }

    /**
     * Set manufacturer.
     *
     * @param \App\AdminBundle\Entity\Manufacturer|null $manufacturer
     *
     * @return Car
     */
    public function setManufacturer(\App\AdminBundle\Entity\Manufacturer $manufacturer = null)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer.
     *
     * @return \App\AdminBundle\Entity\Manufacturer|null
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }
}
