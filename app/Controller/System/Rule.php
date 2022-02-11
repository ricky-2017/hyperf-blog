<?php

namespace App\Controller\System;

use App\Controller\System\AuthController;
use App\Dto\System\RuleReq;
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

    public function lists()
    {
        return jsonSuccess($this->service->lists(PagingReq::fromRequest(), RuleReq::fromRequest()));
    }

    public function get()
    {
        $rule = $this->service->get($this->request->query('id'));
        $rule['resources'] = $rule['rule_resources'];
        return jsonSuccess($rule);
    }

    public function post()
    {
        return jsonSuccess($this->service->post(RuleReq::fromRequest()));
    }

    public function put()
    {
        return jsonSuccess($this->service->put($this->request->query('id'), RuleReq::fromRequest()));
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