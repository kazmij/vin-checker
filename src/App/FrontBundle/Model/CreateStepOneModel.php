<?php

namespace App\FrontBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CreateStepOneModel
{
    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getFileCamera()
    {
        return $this->fileCamera;
    }

    /**
     * @param mixed $fileCamera
     */
    public function setFileCamera($fileCamera)
    {
        $this->fileCamera = $fileCamera;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/png", "image/jpg", "image/jpeg"},
     *     mimeTypesMessage = "Please upload a valid image (jpg, png)"
     * )
     */
    private $file;

    /**
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/png", "image/jpg", "image/jpeg"},
     *     mimeTypesMessage = "Please upload a valid image (jpg, png)"
     * )
     */
    private $fileCamera;

    private $filePath;

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param mixed $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    public function isValid() {

        return strlen($this->getFilePath());
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if(!$this->getFileCamera() && !$this->getFile()) {
            $context->buildViolation('One of these fields is required.')
                ->atPath('file')
                ->addViolation();
        }
    }
}
