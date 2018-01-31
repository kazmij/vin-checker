<?php

namespace App\AdminBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Seo trait.

 */
trait Seo
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $seoTitle;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $seoDescription;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $seoKeywords;


    /**
     * Set seoTitle
     *
     * @param string $seoTitle
     *
     * @return EventTranslation
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * Get seoTitle
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoDescription
     *
     * @param string $seoDescription
     *
     * @return EventTranslation
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * Get seoDescription
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * Set seoKeywords
     *
     * @param string $seoKeywords
     *
     * @return EventTranslation
     */
    public function setSeoKeywords($seoKeywords)
    {
        $this->seoKeywords = $seoKeywords;

        return $this;
    }

    /**
     * Get seoKeywords
     *
     * @return string
     */
    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

}
