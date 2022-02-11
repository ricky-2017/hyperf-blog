<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/3/6
 * Time: 10:58
 */

namespace App\Dto\System;

use App\Common\dto\BaseDto;

class UploadReq extends BaseDto
{
    private $inputName;
    private $file;
    private $fileType;
    private $maxSize;
    private $cropX;
    private $cropY;
    private $cropW;
    private $cropH;

    /**
     * @return mixed
     */
    public function getInputName()
    {
        return $this->inputName;
    }

    /**
     * @param mixed $inputName
     */
    public function setInputName($inputName)
    {
        $this->inputName = $inputName;
    }

    /**
     * @return mixed
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }

    /**
     * @param mixed $maxSize
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * @return mixed
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param mixed $fileType
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getCropX()
    {
        return $this->cropX;
    }

    /**
     * @param mixed $cropX
     */
    public function setCropX($cropX)
    {
        $this->cropX = $cropX;
    }

    /**
     * @return mixed
     */
    public function getCropY()
    {
        return $this->cropY;
    }

    /**
     * @param mixed $cropY
     */
    public function setCropY($cropY)
    {
        $this->cropY = $cropY;
    }

    /**
     * @return mixed
     */
    public function getCropW()
    {
        return $this->cropW;
    }

    /**
     * @param mixed $cropW
     */
    public function setCropW($cropW)
    {
        $this->cropW = $cropW;
    }

    /**
     * @return mixed
     */
    public function getCropH()
    {
        return $this->cropH;
    }

    /**
     * @param mixed $cropH
     */
    public function setCropH($cropH)
    {
        $this->cropH = $cropH;
    }
}