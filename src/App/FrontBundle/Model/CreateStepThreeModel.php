<?php

namespace App\FrontBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

class CreateStepThreeModel
{

    /**
     * @Assert\NotBlank()
     */
    protected $petName;

    protected $city;

    protected $state;

    /**
     * @Assert\Url()
     */
    protected $websiteUrl;

    protected $stat1Id;

    protected $stat1Value;

    protected $stat2Id;

    protected $stat2Value;

    protected $stat3Id;

    protected $stat3Value;

    protected $stat4Id;

    protected $stat4Value;

    /**
     * //@Recaptcha\IsTrue
     */
    protected $captchaCode;

    /**
     * @return mixed
     */
    public function getWebsiteUrl()
    {
        return $this->websiteUrl;
    }

    /**
     * @param mixed $websiteUrl
     */
    public function setWebsiteUrl($websiteUrl)
    {
        $this->websiteUrl = $websiteUrl;
    }

    /**
     * @return mixed
     */
    public function getPetName()
    {
        return $this->petName;
    }

    /**
     * @param mixed $petName
     */
    public function setPetName($petName)
    {
        $this->petName = strip_tags($petName);
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = strip_tags($city);
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getStat1Id()
    {
        return $this->stat1Id;
    }

    /**
     * @param mixed $stat1Id
     */
    public function setStat1Id($stat1Id)
    {
        $this->stat1Id = $stat1Id;
    }

    /**
     * @return mixed
     */
    public function getStat1Value()
    {
        return $this->stat1Value;
    }

    /**
     * @param mixed $stat1Value
     */
    public function setStat1Value($stat1Value)
    {
        $this->stat1Value = strip_tags($stat1Value);
    }

    /**
     * @return mixed
     */
    public function getStat2Id()
    {
        return $this->stat2Id;
    }

    /**
     * @param mixed $stat2Id
     */
    public function setStat2Id($stat2Id)
    {
        $this->stat2Id = $stat2Id;
    }

    /**
     * @return mixed
     */
    public function getStat2Value()
    {
        return $this->stat2Value;
    }

    /**
     * @param mixed $stat2Value
     */
    public function setStat2Value($stat2Value)
    {
        $this->stat2Value = strip_tags($stat2Value);
    }

    /**
     * @return mixed
     */
    public function getStat3Id()
    {
        return $this->stat3Id;
    }

    /**
     * @param mixed $stat3Id
     */
    public function setStat3Id($stat3Id)
    {
        $this->stat3Id = $stat3Id;
    }

    /**
     * @return mixed
     */
    public function getStat3Value()
    {
        return $this->stat3Value;
    }

    /**
     * @param mixed $stat3Value
     */
    public function setStat3Value($stat3Value)
    {
        $this->stat3Value = strip_tags($stat3Value);;
    }

    /**
     * @return mixed
     */
    public function getStat4Id()
    {
        return $this->stat4Id;
    }

    /**
     * @param mixed $stat4Id
     */
    public function setStat4Id($stat4Id)
    {
        $this->stat4Id = $stat4Id;
    }

    /**
     * @return mixed
     */
    public function getStat4Value()
    {
        return $this->stat4Value;
    }

    /**
     * @param mixed $stat4Value
     */
    public function setStat4Value($stat4Value)
    {
        $this->stat4Value = strip_tags($stat4Value);;
    }

    public function isValid() {

        return $this->getPetName();
    }

    public function __toArray() {
        $data = get_object_vars($this);
        unset($data['captchaCode']);

        return $data;
    }
}
