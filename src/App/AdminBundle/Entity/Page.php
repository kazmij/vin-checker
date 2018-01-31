<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Page
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\PageRepository")
 */
class Page extends Content
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * Set type
     *
     * @param string $type
     * @return Page
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
     * @return Page
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
     * @return Page
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

    /**
     * Add petStat
     *
     * @param \App\AdminBundle\Entity\PetStats $petStat
     *
     * @return Page
     */
    public function addPetStat(\App\AdminBundle\Entity\PetStats $petStat)
    {
        $this->petStats[] = $petStat;

        return $this;
    }

    /**
     * Remove petStat
     *
     * @param \App\AdminBundle\Entity\PetStats $petStat
     */
    public function removePetStat(\App\AdminBundle\Entity\PetStats $petStat)
    {
        $this->petStats->removeElement($petStat);
    }

    /**
     * Get petStats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPetStats()
    {
        return $this->petStats;
    }
}
