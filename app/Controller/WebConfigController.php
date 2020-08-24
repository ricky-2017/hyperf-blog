<?php
/**
 * Created by PhpStorm.
 * User: Kepler
 * Date: 2020/8/23
 * Time: 19:03
 */

namespace App\Controller;


use App\Model\Article;
use App\Model\BlogConfig;
use App\Model\Category;
use App\Model\Pages;
use App\Model\Tag;

class WebConfigController extends AbstractController
{
    public function getAboutMe()
    {
        $return['html']   = Pages::query()->where(['type'=>'about'])->value('html') ?? '';
        $qrcode = BlogConfig::query()->select('wxpay_qrcode','alipay_qrcode')->first();
        $return['qrcode'] = $qrcode->toArray();

        return jsonSuccess('success',$return);
    }

    public function getResume()
    {
        $return['html'] = Pages::query()->where('type','resume')->value('html');
        return jsonSuccess('success',$return);
    }

    public function blogInfo()
    {
        $info = BlogConfig::query()->select('blog_name','avatar','sign','github','wxpay_qrcode','alipay_qrcode')->first();
        $info = arrayKeyTrans($info->toArray());

        $info['articleCount']   = Article::query()->where(['status'=>0])->count();
        $info['categoryCount']  = Category::query()->where('article_count','>',0)->count();
        $info['tagCount']       = Tag::query()->where('article_count','>',0)->count();

        return jsonSuccess('success',$info);
    }

    public function getFriends()
    {

    }
}
