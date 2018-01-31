<?php

namespace App\AdminBundle\Entity;

use App\AdminBundle\Entity\Field\Seo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * BlockTranslation
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\BlockTranslationRepository")
 */
class BlockTranslation extends ContentTranslation
{
    use ORMBehaviors\Translatable\Translation;


    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return BlockTranslation
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }
}
