<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 17:43
 */

namespace App\Service\System\impl;


use App\Constants\ReturnCode;
use App\Dto\PagingReq;
use App\Model\System\Rule;
use App\Service\System\RuleService;
use App\Dto\System\RuleReq;

class RuleServiceImpl implements RuleService
{

    /**
     * @var Rule
     */
    private $rule;

    /**
     * RuleServiceImpl constructor.
     * @param Rule $rule
     */
    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }


    function lists(PagingReq $paging, RuleReq $search)
    {
        $query = $this->rule->orderby('rule_name');
//        $searchKeys = $search->keys();
//        $searchValues = $search->toArray();
//        if (!empty($search)) {
//            $query->withSearch($searchKeys, $searchValues);
//        }

//        return $query->paginate($paging);
        return $query->paginate();
    }

    function get($id)
    {
        $data = $this->rule->find($id);

        if (empty($data)) {
            bizException(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND);
        }

        return $data->append(['rule_resources']);
    }

    function post(RuleReq $req)
    {
        $data = [
            'rule_group' => $req->getGroup(),
            'rule_name' => $req->getName(),
            'rule_status' => $req->getStatus()
        ];
        if (empty($data['rule_group']) || !in_array($data['rule_group'], config('service.SYS_GROUP'))) {
            bizException(ReturnCode::INVALID_PARAM, '后台分组标识有误');
        }
//        $validate = new \app\system\validate\Rule();
//        if (!$validate->scene('save')->check($data)) {
//            bizException(ReturnCode::INVALID_PARAM, $validate->getError());
//        }
        $findRs = $this->rule->where('rule_name', $data['rule_name'])
            ->where('rule_group', $data['rule_group'])
            ->first();
        if ($findRs) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, "权限集已存在");
        }
        $this->rule->save($data);
        return $this->rule;
    }

    function put($id, RuleReq $req)
    {
        $data = [
            'rule_id' => $id,
            'rule_name' => $req->getName(),
        ];
        if ($req->getStatus()) {
            $data['rule_status'] = $req->getStatus();
        }
//        $validate = new \app\system\validate\Rule();
//        if (!$validate->scene('update_name')->check($data)) {
//            bizException(ReturnCode::INVALID_PARAM, $validate->getError());
//        }
        $findRs = $this->rule->where('rule_id', '<>', $data['rule_id'])
            ->where('rule_name', $data['rule_name'])
            ->first();
        if ($findRs) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, "权限集已存在");
        }
        $this->rule->find($id)->save($data);
        return $this->rule;
    }

    function patch($id, RuleReq $req)
    {
        $data = [
            'rule_id' => $id,
            'rule_name' => $req->getName(),
        ];
        if ($req->getStatus()) {
            $data['rule_status'] = $req->getStatus();
        }
//        $validate = new \app\system\validate\Rule();
//        if (!$validate->scene('update_name')->check($data)) {
//            bizException(ReturnCode::INVALID_PARAM, $validate->getError());
//        }
        $findRs = $this->rule->where('rule_id', '<>', $data['rule_id'])
            ->where('rule_name', $data['rule_name'])
            ->first();
        if ($findRs) {
            bizException(ReturnCode::DUPLICATE_DATA_NOT_ALLOW, "权限集已存在");
        }
        $this->rule->find($id)->save($req->toArray(true));
        return $this->rule;
    }

    function delete($id)
    {
        return $this->rule->where("rule_id", $id)->delete();
    }

    function putStatus($id)
    {
        $rule = $this->rule->find($id);
        $rule->save(['rule_status' => !$rule['rule_status']]);
        return $this->rule;
    }

    function putResources($id, $resourceIds, $resourceType)
    {
        if (empty($resourceIds)) {
            bizException(ReturnCode::INVALID_PARAM, '缺失[resource_ids]参数');
        }
        $this->rule->replaceRuleResources($id, $resourceIds, $resourceType);
    }


}