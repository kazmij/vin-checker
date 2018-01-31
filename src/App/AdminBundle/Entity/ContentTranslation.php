<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\AdminBundle\Entity\Field\Seo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * ContentTranslation
 *
 * @ORM\Table(name="content_translation")
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\ContentTranslationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="content_translation_type", type="string")
 * @ORM\DiscriminatorMap({
 * "default" = "ContentTranslation",
 * "page_translation" = "PageTranslation",
 * "block_translation" = "BlockTranslation",
 * "setting_translation" = "SettingTranslation"
 * })
 */
class ContentTranslation
{
    use ORMBehaviors\Translatable\Translation;
    use ORMBehaviors\Sluggable\Sluggable;
    use Seo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $shortDescription;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    protected $place;

    public function getSluggableFields()
    {
        return [ 'name' ];
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
     * Set name
     *
     * @param string $name
     *
     * @return PageTranslation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PageTranslation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set place
     *
     * @param string $place
     * @return EventTranslation
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return ContentTranslation
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
