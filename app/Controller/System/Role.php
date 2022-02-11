<?php

namespace App\Controller\System;

use App\Dto\System\RoleReq;
use App\Service\System\RoleService;
use App\Dto\PagingReq;

class Role extends AuthController
{
    private $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    public function lists()
    {
        return jsonSuccess($this->service->lists(PagingReq::fromRequest(), RoleReq::fromRequest()));
    }

    public function get()
    {
        return jsonSuccess($this->service->get($this->request->query('id')));
    }

    public function post()
    {
        return jsonSuccess($this->service->post(RoleReq::fromRequest()));
    }

    public function put()
    {
        return jsonSuccess($this->service->put($this->request->input('id'), RoleReq::fromRequest()));
    }

    public function delete()
    {
        return jsonSuccess($this->service->delete($this->request->input('id')));
    }

    public function putStatus()
    {
        return jsonSuccess($this->service->putStatus($this->request->input('id')));
    }

    public function putRules()
    {
        return jsonSuccess($this->service->putRules($this->request->input('id'), $this->request->input('rule_ids')));
    }
}