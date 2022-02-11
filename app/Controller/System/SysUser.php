<?php

namespace App\Controller\System;

use App\Controller\System\AuthController;
use App\Dto\System\UserReq;
use App\Service\System\UserService;
use App\Dto\PagingReq;
use Hyperf\Utils\Context;

class SysUser extends AuthController
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    public function lists()
    {
        return jsonSuccess($this->service->lists(PagingReq::fromRequest(), UserReq::fromRequest()));
    }

    public function get()
    {
        return jsonSuccess($this->service->get($this->request->query('id')));
    }

    public function getMyUser()
    {
        $user_id = Context::get('sys_user_id');
        return jsonSuccess($this->service->get($user_id));
    }

    public function post(UserReq $req)
    {
        return jsonSuccess($this->service->post($req));
    }

    public function put(UserReq $req)
    {
        return jsonSuccess($this->service->put($this->request->query('id'), $req));
    }

    public function delete()
    {
        return jsonSuccess($this->service->delete($this->request->query('id')));
    }

    public function putStatus()
    {
        return jsonSuccess($this->service->putStatus($this->request->query('id')));
    }

    public function putPassword($password)
    {
        return jsonSuccess($this->service->putPassword($this->request->query('id'), $password));
    }

    public function putRoles($role_ids)
    {
        return jsonSuccess($this->service->putRoles($this->request->query('id'), $role_ids));
    }
}