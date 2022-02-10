<?php

namespace App\Controller\System;

use App\Controller\System\AuthController;
use app\system\dto\RoleReq;
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

    public function lists(PagingReq $paging, RoleReq $search)
    {
        return $this->service->lists($paging, $search);
    }

    public function get()
    {
        return $this->service->get($this->request->query('id'));
    }

    public function post(RoleReq $req)
    {
        return $this->service->post($req);
    }

    public function put(RoleReq $req)
    {
        return $this->service->put($this->request->query('id'), $req);
    }

    public function delete()
    {
        return $this->service->delete($this->request->query('id'));
    }

    public function putStatus()
    {
        return $this->service->putStatus($this->request->query('id'));
    }

    public function putRules($rule_ids)
    {
        return $this->service->putRules($this->request->query('id'), $rule_ids);
    }
}