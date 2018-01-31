<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Block
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\BlockRepository")
 */
class Block extends Content
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
     * Set hash
     *
     * @param string $hash
     *
     * @return Block
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
     * @return Block
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
