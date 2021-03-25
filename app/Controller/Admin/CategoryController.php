<?php

namespace App\Controller\Admin;

use App\Constants\ReturnCode;
use App\Controller\AbstractController;
use App\Model\Article;
use App\Model\Category;
use Hyperf\DbConnection\Db;

class CategoryController extends AbstractController
{
    function getCategory()
    {
        $categoryId = $this->request->query('categoryId');

        $field = [
            'id',
            'name',
            'article_count as articleCount'
        ];

        $data = Category::query()->where('id','=',$categoryId)->select($field)->get();

        return jsonSuccess('success',$data);
    }

    function list()
    {
        $pageSize = $this->request->query('pageSize',10);

        $field = [
            'article_count as articleCount',
            'create_time as createTime',
            'update_time as updateTime',
            'id as categoryId',
            'name as categoryName',
            'status',
            'can_del as canDel',
        ];

        $data = Category::query()->paginate($pageSize);
        $count = Category::query()->count();

        $return = [
            'count'     => $count,
            'list'      => $data->items(),
            'page'      => $data->currentPage(),
            'pageSize'  => $data->perPage(),
        ];

        return jsonSuccess('success',$return);

        $data = Db::table('category')->select($field)->paginate($pageSize);
        $count = Db::table('category')->count();

        $return = [
            'count'     => $count,
            'list'      => $data->items(),
            'page'      => $data->currentPage(),
            'pageSize'  => $data->perPage(),
        ];

        return jsonSuccess('success',$return);
    }

    function add()
    {
        $categoryName = $this->request->post('categoryName');

        $tag = new Category();
        $tag->name = $categoryName;
        $tag->id = create_id();
        $tag->save();

        return jsonSuccess();
    }

    function modify()
    {
        $categoryId = $this->request->post('categoryId');
        $categoryName = $this->request->post('categoryName');

        Category::query()->where('id','=',$categoryId)->update(['name'=>$categoryName]);

        return jsonSuccess();
    }

    function delCategory()
    {
        $category_id = $this->request->post('categoryId');
        $result = Article::query()->where('category_id','=',$category_id)->exists();
        if($result)
            bizException(ReturnCode::DATA_CONSTRAINT_ERROR,'该分类下有文章，目前无法删除');
        else{
            Category::query()->where('id','=',$category_id)->delete();
            return jsonSuccess();
        }
    }

}
