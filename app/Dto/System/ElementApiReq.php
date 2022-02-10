<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12
 * Time: 15:18
 */

namespace app\system\dto;


use App\Common\dto\BaseDto;


class ElementApiReq extends BaseDto
{
    private $apiEndpoints;

    /**
     * @return mixed
     */
    public function getApiEndpoints()
    {
        return $this->apiEndpoints;
    }

    /**
     * @param mixed $apiEndpoints
     */
    public function setApiEndpoints($apiEndpoints)
    {
        $this->apiEndpoints = $apiEndpoints;
    }


}