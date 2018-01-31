<?php

namespace App\FrontBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

class CreateStepConfirmModel
{

    protected $firstname;

    protected $zip;

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     */
    public function setZip($zip)
    {
        $this->zip = strip_tags($zip);
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = strip_tags($lastname);
    }

    /**
     * @return mixed
     */
    public function getVideoProvider()
    {
        return $this->videoProvider;
    }

    /**
     * @param mixed $videoProvider
     */
    public function setVideoProvider($videoProvider)
    {
        $this->videoProvider = $videoProvider;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = strip_tags($email);
    }

    protected $lastname;

    /**
     * @Assert\Email()
     */
    protected $email;

    protected $videoProvider;

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = strip_tags($firstname);
    }

    /**
     * @Recaptcha\IsTrue
     */
    protected $captchaCode;


    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

}
