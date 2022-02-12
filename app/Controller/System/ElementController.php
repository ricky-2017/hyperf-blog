<?php

namespace App\Controller\System;


use App\Controller\AbstractController;
use App\Dto\System\ElementApiReq;
use App\Dto\System\ElementReq;
use App\Dto\System\ElementSearchReq;
use App\Service\System\ElementService;
use App\Dto\PagingReq;
use Hyperf\Utils\Context;

class ElementController extends AbstractController
{
    private $service;

    public function __construct(ElementService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    public function lists()
    {
        return jsonSuccess($this->service->lists(PagingReq::fromRequest(), ElementSearchReq::fromRequest()));
    }

    public function listTree()
    {
        return jsonSuccess($this->service->listTree(
            $this->request->query('depth', -1),
            $this->request->query('type', null),
            $this->request->query('ele_group'))
        );
    }

    public function listMyTree()
    {
        return jsonSuccess($this->service->listTree(
            $this->request->query('depth', -1),
            $this->request->query('type', null),
            Context::get('user_group'),
            $this->request->query('sys_user_id', null))
        );
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