<?php

namespace App\Controller\System;


use app\system\dto\ElementApiReq;
use app\system\dto\ElementReq;
use app\system\dto\ElementSearchReq;
use App\Service\System\ElementService;
use App\Dto\PagingReq;
use Hyperf\Utils\Context;

class ElementController extends AuthController
{
    private $service;

    public function __construct(ElementService $service)
    {
        $this->service = $service;
    }

    public function lists(PagingReq $paging, ElementSearchReq $search)
    {
        return jsonSuccess($this->service->lists($paging, $search));
    }

    public function listTree($depth = -1, $type = null)
    {
        $param = $this->request->all();
        return jsonSuccess($this->service->listTree($depth, $type, $param['ele_group']));
    }

    public function listMyTree($depth = -1, $types = null, $sys_user_id = null)
    {
        return jsonSuccess($this->service->listTree($depth, $types, Context::get('user_group'), $sys_user_id));
    }

    public function get()
    {
        return jsonSuccess($this->service->get($this->request->query('id')));
    }

    public function post(ElementReq $req, ElementApiReq $elementApiReq)
    {
        return jsonSuccess($this->service->post($req, $elementApiReq));
    }

    public function patch(ElementReq $req, ElementApiReq $elementApiReq)
    {
        $this->service->patch($this->request->query('id'), $req, $elementApiReq);
    }

    public function put(ElementReq $req, ElementApiReq $elementApiReq)
    {
        $this->service->put($this->request->query('id'), $req, $elementApiReq);
    }

    public function delete()
    {
        $this->service->delete($this->request->query('id'));
    }

    public function getMyButtonsPrivilege()
    {
        return jsonSuccess($this->service->getMyButtonsPrivilege(Context::get('sys_user_id'), Context::get('user_group')));
    }
}