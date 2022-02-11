<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12
 * Time: 15:18
 */

namespace App\Dto\System;


use App\Common\dto\BaseDto;

class ElementReq extends BaseDto
{
    private $eleParentId;
    private $eleName;
    private $eleKey;
    private $eleIcon;
    private $eleType;
    private $eleSort;
    private $eleGroup;
    private $isSystemLog;

    /**
     * @return mixed
     */
    public function getEleParentId()
    {
        return $this->eleParentId;
    }

    /**
     * @param mixed $eleParentId
     */
    public function setEleParentId($eleParentId)
    {
        $this->eleParentId = $eleParentId;
    }

    /**
     * @return mixed
     */
    public function getEleName()
    {
        return $this->eleName;
    }

    /**
     * @param mixed $eleName
     */
    public function setEleName($eleName)
    {
        $this->eleName = $eleName;
    }

    /**
     * @return mixed
     */
    public function getEleKey()
    {
        return $this->eleKey;
    }

    /**
     * @param mixed $eleKey
     */
    public function setEleKey($eleKey)
    {
        $this->eleKey = $eleKey;
    }

    /**
     * @return mixed
     */
    public function getEleIcon()
    {
        return $this->eleIcon;
    }

    /**
     * @param mixed $eleIcon
     */
    public function setEleIcon($eleIcon)
    {
        $this->eleIcon = $eleIcon;
    }

    /**
     * @return mixed
     */
    public function getEleType()
    {
        return $this->eleType;
    }

    /**
     * @param mixed $eleType
     */
    public function setEleType($eleType)
    {
        $this->eleType = $eleType;
    }

    /**
     * @return mixed
     */
    public function getEleSort()
    {
        return $this->eleSort;
    }

    /**
     * @param mixed $eleSort
     */
    public function setEleSort($eleSort)
    {
        $this->eleSort = $eleSort;
    }

    /**
     * @return mixed
     */
    public function getEleGroup()
    {
        return $this->eleGroup;
    }

    /**
     * @param mixed $eleGroup
     */
    public function setEleGroup($eleGroup)
    {
        $this->eleGroup = $eleGroup;
    }

    /**
     * @return mixed
     */
    public function getIsSystemLog()
    {
        return $this->isSystemLog;
    }

    /**
     * @param mixed $isSystemLog
     */
    public function setIsSystemLog($isSystemLog)
    {
        $this->isSystemLog = $isSystemLog;
    }
}