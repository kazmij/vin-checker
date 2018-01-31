<?php

namespace App\AdminBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category trait.

 */
trait Category
{
    /**
     * Category
     * @ORM\ManyToOne(targetEntity="Category", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $category;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
}
