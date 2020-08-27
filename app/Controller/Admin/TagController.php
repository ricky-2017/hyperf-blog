<?php

namespace App\Controller\Admin;



use App\Constants\ReturnCode;
use App\Controller\AbstractController;
use App\Model\Tag;
use Hyperf\DbConnection\Db;

class TagController extends AbstractController
{
    function getTag()
    {
        $tagId = $this->request->query('tagId');
        $field = ['id', 'name', 'article_count as articleCount'];
        $data = Tag::query()->where('id','=',$tagId)->select($field)->get();

        return jsonSuccess('success',$data);
    }

    function tagList()
    {
        $pageSize = $this->request->query('pageSize',10);
        $field = [
            'article_count as articleCount',
            'create_time as createTime',
            'update_time as updateTime',
            'id as tagId',
            'name as tagName',
            'status as status',
        ];

        $data = Tag::query()->select($field)->paginate($pageSize);

        $return = [
          'count' => $data->total(),
          'list'  => $data->items(),
          'page'  => $data->currentPage(),
          'pageSize' => $data->perPage(),
        ];

        return jsonSuccess('success',$return);
    }

    function addTag()
    {
        $tagName = $this->request->post('tagName');

        $tag = new Tag();
        $tag->name = $tagName;
        $tag->id = create_id();
        $tag->save();

        return jsonSuccess();
    }

    function modifyTag()
    {
        $tagId = $this->request->post('tagId');
        $tagName = $this->request->post('tagName');

        Tag::query()->where('id','=',$tagId)->update(['name'=>$tagName]);

        return jsonSuccess();
    }

    function delTag()
    {
        $tagId = $this->request->post('tagId');
        try{
            DB::beginTransaction();

            DB::table('tag')->where('id','=',$tagId)->delete();
            DB::table('article_tag_mapper')->where('tag_id','=',$tagId)->delete();

            DB::commit();
        }catch (\Exception $e)
        {
            DB::rollBack();
            bizException(ReturnCode::DB_OPERATION_ERROR);
        }

        return jsonSuccess();
    }

}
