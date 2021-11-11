<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/9
 * Time: 18:09
 */

namespace app\Model\system;

use App\Model\Model;

class Element extends Model
{
    protected $table = 'sys_element';
    protected $primaryKey = "ele_id";
    const CREATED_AT = 'ele_create_time';
    const UPDATED_AT = 'ele_update_time';

    /**
     * 对数的同一层级进行重排序
     * @param integer $id 改变了排序的元素id
     * @param integer $parent_id 父级元素id
     * @param string $type 元素类型
     * @param integer $sort 改变了排序的元素的排序值
     * @param integer int $step 步长，一般只取(+-)1
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function resort($id, $parent_id, $type, $sort, $step = 1)
    {
        $this->where('ele_id', '<>', $id)
            ->where("ele_parent_id", "=", $parent_id)
            ->where("ele_type", "=", $type)
            ->where("ele_sort", ">=", $sort)
            ->increment('ele_sort', $step);
    }

    /**
     * 列出第一层的元素
     * @param null $type 类型
     * @param null $userId 用户id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listFirstLayer($type = null, $userId = null, $group = '')
    {
        $query = $this->where('ele_parent_id', '=', 0)
            ->where('ele_group', '=', $group);

        $query->whereIn('ele_type', ['MENU']);

        if ($userId) {
            $query = $query->where("ele_id", "IN", $this->listUserElementIds($userId));
        }

        return $query->orderBy('ele_sort')
            ->with(['apiPoints'])
            ->select()
            ->each(function ($item) {
                if (!empty($item['api_points'])) {
                    $pointsArr = array_column($item['api_points']->toArray(), 'api_endpoint');
                    $item['api_endpoints'] = implode(',', $pointsArr);
                }
            })
            ->toArray();
    }

    /**
     * 列出除第一层以外的元素
     * @param null $type 类型
     * @param null $userId 用户id
     * @param $group
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listExcludeFirstLayer($type = null, $userId = null, $group = '')
    {
        $query = $this->where('ele_parent_id', '<>', 0)
            ->where('ele_group', '=', $group);

        $query->whereIn('ele_type', ['PAGE', 'BUTTON']);

        if ($userId) {
            $query->where("ele_id", "IN", $this->listUserElementIds($userId));
        }

        return $query->orderBy('ele_sort')
            ->with(['apiPoints'])
            ->select()
            ->each(function ($item) {
                if (!empty($item['api_points'])) {
                    $pointsArr = array_column($item['api_points']->toArray(), 'api_endpoint');
                    $item['api_endpoints'] = implode(',', $pointsArr);
                }
            })
            ->toArray();
    }

    /**
     * 列出用户拥有的所有元素id
     * @param integer|string $userId 用户id
     * @return array 元素id数组
     */
    private function listUserElementIds($userId)
    {
        $roleIds = UserRole::where("user_id", $userId)->pluck('role_id');
        $ruleIds = RoleRule::where("role_id", "IN", $roleIds)->pluck('rule_id');
        $eleIds = RuleResource::where("rule_id", "IN", $ruleIds)
            ->where("resource_type", "ELEMENT")
            ->pluck('resource_id');
        // 获取父节点IDS fix(不全选某个权限,没有保存父级ID的问题)
        $parentEleIds = Element::where('ele_id', 'in', $eleIds)->where('ele_parent_id', 'neq', 0)->pluck('ele_parent_id');
        return (array_unique(array_merge($eleIds, $parentEleIds)));
    }

    // 搜索器

    function searchEleNameAttr($query, $value, $data)
    {
        $query->whereLike('ele_name', "%$value%");
    }

    function searchEleKeyAttr($query, $value, $data)
    {
        $query->whereLike('ele_key', "%$value%");
    }

    function searchEleTypeAttr($query, $value, $data)
    {
        $query->where('ele_type', $value);
    }

    public function apiPoints()
    {
        return $this->hasMany(ElementApi::class, 'sys_element_id', 'ele_id');
    }
}