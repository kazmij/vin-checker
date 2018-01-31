<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Manufacturer
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\ManufacturerRepository")
 */
class Manufacturer extends Content
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $apiId;

    /**
     * @ORM\OneToMany(targetEntity="Model", mappedBy="manufacturer", cascade={"persist", "remove"})
     */
    protected $models;

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Manufacturer
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set country.
     *
     * @param string|null $country
     *
     * @return Manufacturer
     */
    public function setCountry($country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set apiId.
     *
     * @param string|null $apiId
     *
     * @return Manufacturer
     */
    public function setApiId($apiId = null)
    {
        $this->apiId = $apiId;

        return $this;
    }

    /**
     * Get apiId.
     *
     * @return string|null
     */
    public function getApiId()
    {
        return $this->apiId;
    }

    /**
     * Add model.
     *
     * @param \App\AdminBundle\Entity\Model $model
     *
     * @return Manufacturer
     */
    public function addModel(\App\AdminBundle\Entity\Model $model)
    {
        $this->models[] = $model;

        return $this;
    }

    /**
     * Remove model.
     *
     * @param \App\AdminBundle\Entity\Model $model
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeModel(\App\AdminBundle\Entity\Model $model)
    {
        return $this->models->removeElement($model);
    }

    /**
     * Get models.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModels()
    {
        return $this->models;
    }

    public function toChoice() {
        return [
            $this->getName() => $this->getId()
        ];
    }
}
