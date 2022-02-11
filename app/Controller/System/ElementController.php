<?php

namespace App\Controller\System;


use App\Dto\System\ElementApiReq;
use App\Dto\System\ElementReq;
use App\Dto\System\ElementSearchReq;
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

    public function lists()
    {
        return jsonSuccess($this->service->lists(PagingReq::fromRequest(), ElementSearchReq::fromRequest()));
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

    public function post()
    {
        return jsonSuccess($this->service->post(ElementReq::fromRequest(), ElementApiReq::fromRequest()));
    }

    public function patch()
    {
        $this->service->patch($this->request->query('id'), ElementReq::fromRequest(), ElementApiReq::fromRequest());
    }

    public function put()
    {
        $this->service->put($this->request->query('id'), ElementReq::fromRequest(), ElementApiReq::fromRequest());
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