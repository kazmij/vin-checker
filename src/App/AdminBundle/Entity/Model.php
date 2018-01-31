<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Model
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\ModelRepository")
 */
class Model extends Content
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
     * @ORM\ManyToOne(targetEntity="Manufacturer", inversedBy="models", cascade={"persist"})
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $manufacturer;

    /**
     * @ORM\OneToMany(targetEntity="ModelTrim", mappedBy="model", cascade={"persist", "remove"})
     */
    protected $trims;


    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Model
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
     * Set manufacturer.
     *
     * @param \App\AdminBundle\Entity\Manufacturer|null $manufacturer
     *
     * @return Model
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

    /**
     * Add trim.
     *
     * @param \App\AdminBundle\Entity\ModelTrim $trim
     *
     * @return Model
     */
    public function addTrim(\App\AdminBundle\Entity\ModelTrim $trim)
    {
        $this->trims[] = $trim;

        return $this;
    }

    /**
     * Remove trim.
     *
     * @param \App\AdminBundle\Entity\ModelTrim $trim
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTrim(\App\AdminBundle\Entity\ModelTrim $trim)
    {
        return $this->trims->removeElement($trim);
    }

    /**
     * Get trims.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrims()
    {
        return $this->trims;
    }
}
