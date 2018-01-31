<?php

namespace App\FrontBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CreateStepTwoModel
{

    private $adoptMe;

    /**
     * @Assert\NotBlank()
     */
    private $imageData;

    /**
     * @return mixed
     */
    public function getContainerData()
    {
        return $this->containerData;
    }

    /**
     * @param mixed $containerData
     */
    public function setContainerData($containerData)
    {
        $this->containerData = $containerData;
    }

    /**
     * @return mixed
     */
    public function getCanvasData()
    {
        return $this->canvasData;
    }

    /**
     * @param mixed $canvasData
     */
    public function setCanvasData($canvasData)
    {
        $this->canvasData = $canvasData;
    }

    private $imageCanvas;

    private $cropData;

    protected $containerData;

    protected $canvasData;

    /**
     * @return mixed
     */
    public function getFrame()
    {
        return $this->frame;
    }

    /**
     * @param mixed $frame
     */
    public function setFrame($frame)
    {
        $this->frame = $frame;
    }

    /**
     * @Assert\NotBlank()
     */
    private $frame;

    /**
     * @return mixed
     */
    public function getCropData()
    {
        return $this->cropData;
    }

    /**
     * @param mixed $cropData
     */
    public function setCropData($cropData)
    {
        $this->cropData = $cropData;
    }

    /**
     * @return mixed
     */
    public function getImageCanvas()
    {
        return $this->imageCanvas;
    }

    /**
     * @param mixed $imageCanvas
     */
    public function setImageCanvas($imageCanvas)
    {
        $this->imageCanvas = $imageCanvas;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param mixed $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return mixed
     */
    public function getImageData()
    {
        return $this->imageData;
    }

    /**
     * @param mixed $imageData
     */
    public function setImageData($imageData)
    {
        $this->imageData = $imageData;
    }

    /**
     * @return mixed
     */
    public function getAdoptMe()
    {
        return $this->adoptMe;
    }

    /**
     * @param mixed $adoptMe
     */
    public function setAdoptMe($adoptMe)
    {
        $this->adoptMe = $adoptMe;
    }

    private $filter;

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

    public function isValid()
    {

        return $this->getFrame() && $this->getCropData();
    }

}
