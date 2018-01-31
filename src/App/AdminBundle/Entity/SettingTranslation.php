<?php

namespace App\AdminBundle\Entity;

use App\AdminBundle\Entity\Field\Seo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * SettingTranslation
 *
 * @ORM\Entity(repositoryClass="App\AdminBundle\Repository\SettingTranslationRepository")
 */
class SettingTranslation extends ContentTranslation
{
    use ORMBehaviors\Translatable\Translation;

}
