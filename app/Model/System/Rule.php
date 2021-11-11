<?php

namespace app\Model\system;


use App\Model\Model;
use Hyperf\DbConnection\Db;

class Rule extends Model
{
    protected $table = 'sys_rule';
    protected $primaryKey = 'rule_id';
//    protected $readonly = ['rule_id', 'rule_create_time'];
    const CREATED_AT = 'rule_create_time';
    const UPDATED_AT = 'rule_update_time';

    public function searchRuleGroupAttr($query, $value, $data)
    {
        $query->where('rule_group', is_array($value) ? 'in' : 'eq', $value);
    }

    /**
     * 权限集关联权限
     * @param $id [权限集id]
     * @param $resourceIds [元素|api的ids]
     * @param $resourceType [ELEMENT | API]
     */
    function replaceRuleResources($id, $resourceIds, $resourceType)
    {
        $ruleInfo = Rule::where('rule_id', $id)->get();
        $parentIds = $this->getParentResourcesIds($resourceType, $resourceIds);
        $resourceIds = array_unique(array_merge($resourceIds, $parentIds));
        sort($resourceIds);
        Db::beginTransaction();
        RuleResource::where('resource_type', $resourceType)
            ->where('rule_id', $id)
            ->delete();
        RuleResource::create(array_map(function ($v) use ($id, $resourceType, $ruleInfo) {
                return [
                    'rule_resource_group' => $ruleInfo['rule_group'],
                    'rule_id' => $id,
                    'resource_id' => $v,
                    'resource_type' => $resourceType,
                ];
            }, $resourceIds));

        Db::commit();
    }

    function getRuleResourcesAttr($value, $data)
    {
        $list = RuleResource::with('resource')->where('rule_id', $data['rule_id'])->select()->toArray();

        $ruleResources = array();
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                if (!empty($v['resource'])) {
                    $ruleResources[$v['resource_type']][] = $v['resource'];
                }
            }
        }

        return $ruleResources;
    }

    /**
     * 递归获取父级parent_id
     * @param $resourceType
     * @param $resourceIds
     * @return array
     */
    private function getParentResourcesIds($resourceType, &$resourceIds)
    {
        $parentResult = [];
        if ($resourceType == 'ELEMENT') {
            $parentIds = Element::where('ele_id', 'in', $resourceIds)
                ->pluck('ele_parent_id');
        } elseif ($resourceType == 'API') {
//            $parentIds = Api::where('api_id', 'in', $resourceIds)
//                ->pluck('api_parent_id');
        }

        foreach ($parentIds as $index => $parentId) {
            if ($parentId == 0) {
                unset($parentIds[$index]);
            }
        }

        $parentResult = array_unique(array_merge($parentResult, $parentIds));
        if (!empty($parentIds)) {
            $this->getParentResourcesIds($resourceType, $parentIds);
        }
        return $parentResult;
    }
}