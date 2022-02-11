<?php

namespace App\Controller\System;

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

    public function post()
    {
        return jsonSuccess($this->service->post(UserReq::fromRequest()));
    }

    public function put()
    {
        return jsonSuccess($this->service->put($this->request->input('id'), UserReq::fromRequest()));
    }

    public function delete()
    {
        return jsonSuccess($this->service->delete($this->request->input('id')));
    }

    public function putStatus()
    {
        return jsonSuccess($this->service->putStatus($this->request->input('id')));
    }

    public function putPassword()
    {
        return jsonSuccess($this->service->putPassword($this->request->input('id'), $this->request->input('password')));
    }

    public function putRoles()
    {
        return jsonSuccess($this->service->putRoles($this->request->input('id'), $this->request->input('role_ids')));
    }
}