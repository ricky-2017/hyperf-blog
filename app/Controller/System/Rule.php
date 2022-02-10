<?php

namespace App\Controller\System;

use App\Controller\System\AuthController;
use app\system\dto\RuleReq;
use App\Service\System\RuleService;
use App\Dto\PagingReq;

class Rule extends AuthController
{

    private $service;

    public function __construct(RuleService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    public function lists(PagingReq $paging, RuleReq $search)
    {
        return jsonSuccess($this->service->lists($paging, $search));
    }

    public function get()
    {
        $rule = $this->service->get($this->request->query('id'));
        $rule['resources'] = $rule['rule_resources'];
        return jsonSuccess($rule);
    }

    public function post(RuleReq $req)
    {
        return jsonSuccess($this->service->post($req));
    }

    public function put(RuleReq $req)
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

    public function putResources($resource_ids, $resource_type)
    {
        return jsonSuccess($this->service->putResources($this->request->query('id'), $resource_ids, $resource_type));
    }
}