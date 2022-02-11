<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12
 * Time: 16:42
 */

namespace App\Service\System\impl;

use App\Constants\ReturnCode;
use App\Dto\PagingReq;
use App\Model\System\Element;
use App\Model\System\ElementApi;
use App\Model\System\RoleRule;
use App\Model\System\RuleResource;
use App\Model\System\UserRole;
use App\Service\System\ElementService;
use App\Dto\System\ElementApiReq;
use App\Dto\System\ElementReq;
use App\Dto\System\ElementSearchReq;
use Hyperf\DbConnection\Db;

class ElementServiceImpl implements ElementService
{
    /**
     * @var Element
     */
    private $element;
    private $ruleResource;
    private $elementApi;
    private $userRole;
    private $roleRule;

    /**
     * ElementServiceImpl constructor.
     * @param Element $element
     * @param RuleResource $ruleResource
     * @param ElementApi $elementApi
     * @param UserRole $userRole
     * @param RoleRule $roleRule
     */
    public function __construct(Element $element,
                                RuleResource $ruleResource,
                                ElementApi $elementApi,
                                UserRole $userRole,
                                RoleRule $roleRule)
    {
        $this->element = $element;
        $this->ruleResource = $ruleResource;
        $this->elementApi = $elementApi;
        $this->userRole = $userRole;
        $this->roleRule = $roleRule;
    }

    function lists(PagingReq $paging, ElementSearchReq $search)
    {
        $query = $this->element->orderby('ele_key');

        return $query->paginate();
    }

    function listTree($depth = -1, $type = null, $group = '', $userId = null)
    {
        $trees = $this->element->listFirstLayer($type, $userId, $group);
        $list = $this->element->listExcludeFirstLayer($type, $userId, $group);

        $dictionary = array();
        foreach ($list as $k => $v) {
            $dictionary[$v['ele_parent_id']][] = $v;
        }
        $this->resolveTree($trees, $dictionary, $depth);

        return $trees;
    }

    private function resolveTree(&$trees, $dictionary, &$depth)
    {
        if ($depth != -1 && $depth == 0) {
            return;
        }

        foreach ($trees as $k => &$v) {
            if (isset($dictionary[$v['ele_id']])) {
                $v['children'] = $dictionary[$v['ele_id']];
                if ($depth != -1 && next($trees) == false) {
                    $depth--;
                }
                $this->resolveTree($v['children'], $dictionary, $depth);
            } else {
                $v['children'] = [];
            }
        }

        $depth--;

    }

    function get($id)
    {
        $data = $this->element->find($id);

        if (empty($data)) {
            bizException(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND);
        }

        return $data;
    }

    function post(ElementReq $req, ElementApiReq $elementApiReq)
    {
        if (empty($req->getEleGroup()) || !in_array($req->getEleGroup(), config('service.SYS_GROUP'))) {
            bizException(ReturnCode::INVALID_PARAM, '后台分组标识出错');
        }
        $apiEndpoints = $elementApiReq->getApiEndpoints();
        if (!empty($apiEndpoints)) {
            $apiEndpointsArr = array_filter(explode(',', $apiEndpoints));
            if (empty($apiEndpointsArr)) {
                bizException(ReturnCode::INVALID_PARAM, '提交的接口端点格式有误');
            }
        }

        $parent = $this->element->find($req->getEleParentId());

        if ($req->getEleType() != 'MENU' && empty($parent)) {
            bizException(ReturnCode::DATA_NOT_FOUND, "父级不存在");
        }

        $element = $this->element
            ->where('ele_key', $req->getEleKey())
            ->where('ele_group', $req->getEleGroup())
            ->first();

        if (!empty($element)) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, '元素已存在');
        }

        Db::beginTransaction();

        $this->element->save($req->toArray());
        $elementId = $this->element->ele_id;
        if (isset($apiEndpointsArr) && !empty($apiEndpointsArr)) {
            $saveAll = [];
            foreach ($apiEndpointsArr as $apiEndpoint) {
                array_push($saveAll, [
                    'ele_api_group' => $req->getEleGroup(),
                    'sys_element_id' => $elementId,
                    'api_endpoint' => $apiEndpoint
                ]);
            }
        }

        $this->elementApi->where('sys_element_id', $elementId)->delete();
        if (isset($saveAll) && !empty($saveAll)) {
            foreach ($saveAll as $vo) {
                $this->elementApi->save($vo);
            }
        }

        // 插入后重新排序
        //$this->element->resort($this->element['ele_id'], $req->getParentId(), $req->getType(), $req->getSort());

        Db::commit();

        return $this->element;
    }

    function patch($id, ElementReq $req, ElementApiReq $elementApiReq)
    {
        $element = $this->element->where('ele_id', $id)->first();
        if (empty($element)) {
            bizException(ReturnCode::DATA_NOT_FOUND, '元素不存在');
        }

        $repeat = $this->element
            ->where('ele_key', $req->getEleKey())
            ->where('ele_group', $element['ele_group'])
            ->where('ele_id', '<>', $element['ele_id'])
            ->first();

        if (!empty($repeat)) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, '元素标识已被使用');
        }

        $apiEndpoints = $elementApiReq->getApiEndpoints();
        if (!empty($apiEndpoints)) {
            $apiEndpointsArr = array_filter(explode(',', $apiEndpoints));
            if (empty($apiEndpointsArr)) {
                bizException(ReturnCode::INVALID_PARAM, '提交的接口端点格式有误');
            }
            $apiPoints = [];
            foreach ($apiEndpointsArr as $apiEndpoint) {
                array_push($apiPoints, [
                    'sys_element_id' => $id,
                    'ele_api_group' => $element['ele_group'],
                    'api_endpoint' => $apiEndpoint
                ]);
            }
        }

        Db::beginTransaction();
        $element->save($req->toArray());

        if (isset($apiPoints)) {
            $this->elementApi->where('sys_element_id', $id)->delete();
            foreach ($apiPoints as $vo) {
                $this->elementApi->save($vo);
            }
        }

        Db::commit();
        return true;
    }

    function put($id, ElementReq $req, ElementApiReq $elementApiReq)
    {
        $element = $this->element->where('ele_id', $id)->first();
        if (empty($element)) {
            bizException(ReturnCode::DATA_NOT_FOUND, '元素不存在');
        }

        $repeat = $this->element
            ->where('ele_key', $req->getEleKey())
            ->where('ele_group', $element['ele_group'])
            ->where('ele_id', '<>', $element['ele_id'])
            ->first();

        if (!empty($repeat)) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, '元素标识已被使用');
        }

        $apiEndpoints = $elementApiReq->getApiEndpoints();
        if (!empty($apiEndpoints)) {
            $apiEndpointsArr = array_filter(explode(',', $apiEndpoints));
            if (empty($apiEndpointsArr)) {
                bizException(ReturnCode::INVALID_PARAM, '提交的接口端点格式有误');
            }
            $apiPoints = [];
            foreach ($apiEndpointsArr as $apiEndpoint) {
                array_push($apiPoints, [
                    'sys_element_id' => $id,
                    'ele_api_group' => $element['ele_group'],
                    'api_endpoint' => $apiEndpoint
                ]);
            }
        }

        Db::beginTransaction();
        $element->save($req->toArray());

        $this->elementApi->where('sys_element_id', $id)->delete();
        if (isset($apiPoints)) {
            $this->elementApi->where('sys_element_id', $id)->delete();
            foreach ($apiPoints as $vo) {
                $this->elementApi->save($vo);
            }
        }

        Db::commit();
        return true;
    }

    function sort($id, $sort)
    {
        Db::beginTransaction();

        $element = $this->element->find($id);

        // 插入后重新排序
        $this->element->resort($id, $element['ele_parent_id'], $element['ele_type'], $sort);

        $this->element->update(['ele_sort' => $sort], ['ele_id' => $id]);

        Db::commit();
    }

    function delete($id)
    {
        Db::beginTransaction();
        $child = $this->element->where('ele_parent_id', $id)->first();
        $element = $this->element->where('ele_id', $id)->first();

        if ($child) {
            bizException(ReturnCode::DATA_CONSTRAINT_ERROR, "必须先删除子节点");
        }

        $this->elementApi->where('sys_element_id', $id)->delete();

        $this->element->where('ele_id', $id)->delete();
        $this->ruleResource->where('resource_id', $id)->delete();

        $this->element->resort($id, $element['ele_parent_id'], $element['ele_type'], $element['ele_sort'], -1);
        Db::commit();
    }

    function batchDelete(array $ids)
    {
        Db::beginTransaction();
        $this->element
            ->whereIn('ele_id', $ids, 'or')
            ->whereIn('ele_parent_id', $ids, 'or')
            ->delete();
        Db::commit();
    }

    function getMyButtonsPrivilege($sysUserId, $userGroup)
    {
        $roleIds = $this->userRole->where('user_id', $sysUserId)->pluck('role_id');
        $ruleIds = $this->roleRule->where('role_id', 'in', $roleIds)->pluck('rule_id');
        $elementIds = $this->ruleResource->where('rule_id', 'in', $ruleIds)->where('resource_type', 'ELEMENT')->pluck('resource_id');
        $elementKeys = $this->element
            ->where('ele_id', 'in', $elementIds)
            ->where('ele_type', 'BUTTON')
            ->where('ele_group', $userGroup)
            ->pluck('ele_key');
        return [
            'user_group' => $userGroup,
            'ele_keys' => $elementKeys
        ];
    }

}