<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model\Tree\NodeInterface;

/**
 * Category
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\CategoryRepository")
 */
class Category extends Content implements ORMBehaviors\Tree\NodeInterface, \ArrayAccess
{

    const TYPE_NEWS = 'news';
    const TYPE_EVENTS = 'events';

    use ORMBehaviors\Tree\Node {
        ORMBehaviors\Tree\Node::setChildNodeOf as setChildNodeOfBackup;
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

    public function setChildNodeOf(NodeInterface $node = null)
    {
        $path = rtrim($node->getRealMaterializedPath(), static::getMaterializedPathSeparator());
        $this->setMaterializedPath($path);

        if (null !== $this->parentNode) {
            $this->parentNode->getChildNodes()->removeElement($this);
        }

        $this->parentNode = $node;
        $this->parentNode->addChildNode($this);

        foreach ($this->getChildNodes() as $child) {
            $child->setChildNodeOf($this);
        }

        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
