<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2022/2/10
 * Time: 11:17
 */

namespace App\Dto;


use App\Common\dto\BaseDto;

class PagingReq extends BaseDto
{
    private $page = 1;
    private $listRows = 15;

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getListRows()
    {
        return $this->listRows;
    }

    /**
     * @param mixed $listRows
     */
    public function setListRows($listRows)
    {
        $this->listRows = $listRows;
    }


}