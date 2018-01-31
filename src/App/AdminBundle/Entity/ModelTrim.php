<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ModelTrim
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\ModelTrimRepository")
 */
class ModelTrim extends Content
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
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $year;

    /**
     * @ORM\ManyToOne(targetEntity="Model", inversedBy="trims", cascade={"persist"})
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $model;


    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return ModelTrim
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
     * Set year.
     *
     * @param int|null $year
     *
     * @return ModelTrim
     */
    public function setYear($year = null)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year.
     *
     * @return int|null
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set model.
     *
     * @param \App\AdminBundle\Entity\Model|null $model
     *
     * @return ModelTrim
     */
    public function setModel(\App\AdminBundle\Entity\Model $model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model.
     *
     * @return \App\AdminBundle\Entity\Model|null
     */
    public function getModel()
    {
        return $this->model;
    }
}
