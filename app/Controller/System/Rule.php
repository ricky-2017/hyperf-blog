<?php

namespace App\Controller\System;

use App\Controller\AbstractController;
use App\Dto\System\RuleReq;
use App\Service\System\RuleService;
use App\Dto\PagingReq;

class Rule extends AbstractController
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
        $rule = $this->service->get($this->request->input('id'));
        $rule['resources'] = $rule['rule_resources'];
        return jsonSuccess($rule);
    }

    public function post()
    {
        return jsonSuccess($this->service->post(RuleReq::fromRequest()));
    }

    public function put()
    {
        return jsonSuccess($this->service->put($this->request->input('id'), RuleReq::fromRequest()));
    }

    public function delete()
    {
        return jsonSuccess($this->service->delete($this->request->input('id')));
    }

    public function putStatus()
    {
        return jsonSuccess($this->service->putStatus($this->request->input('id')));
    }

    public function putResources()
    {
        return jsonSuccess($this->service->putResources($this->request->input('id'), $this->request->input('resource_ids'), $this->request->input('resource_type')));
    }
}