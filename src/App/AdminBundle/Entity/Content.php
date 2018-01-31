<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Content
 *
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\ContentRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="content_type", type="string")
 * @ORM\DiscriminatorMap({
 * "default" = "Content",
 * "page" = "Page",
 * "category" = "Category",
 * "block" = "Block",
 * "manufacturer" = "Manufacturer",
 * "model" = "Model",
 * "model_trim" = "ModelTrim",
 * "setting" = "Setting",
 * "car" = "Car",
 * "car_mileages" = "CarMileageHistory",
 * "car_accident" = "CarAccidentHistory",
 * "car_photo" = "CarPhoto"
 * })
 */
class Content
{

    use ORMBehaviors\Translatable\Translatable;
    use ORMBehaviors\Timestampable\Timestampable;
    //use ORMBehaviors\SoftDeletable\SoftDeletable;
    use Field\Category;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    protected $active = true;


    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $contentClass;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $hash;


    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Content
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Content
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set contentClass
     *
     * @param string $contentClass
     *
     * @return Content
     */
    public function setContentClass($contentClass)
    {
        $this->contentClass = $contentClass;

        return $this;
    }

    /**
     * Get contentClass
     *
     * @return string
     */
    public function getContentClass()
    {
        return $this->contentClass;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Content
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
}
