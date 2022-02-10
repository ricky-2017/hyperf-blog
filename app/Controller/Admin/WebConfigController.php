<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use Hyperf\DbConnection\Db;

class WebConfigController extends AbstractController
{
    function getWebConfig()
    {
        $data = Db::table('blog_config')->first();
        $data = $data->toArray();

        $data['hadOldPassword'] = (empty($data['salt'])) ? false : true;
        unset($data['salt']);
        return jsonSuccess($data);

    }

    function modify()
    {
        $params = $this->request->toArray();
        $config = array();
        $encrypt = cb_encrypt($params['viewPassword']);

        $config['view_password'] = $encrypt['password'];
        $config['salt'] = $encrypt['salt'];
        $config['blog_name'] = $params['blogName'];
        $config['avatar'] = $params['avatar'];
        $config['sign'] = $params['sign'];
        $config['wxpay_qrcode'] = $params['wxpayQrcode'];
        $config['alipay_qrcode'] = $params['alipayQrcode'];
        $config['github'] = $params['github'];

        if (isset($params['id']) && !empty($params['id'])) {
            DB::table('blog_config')->where('id',$params['id'])->update($config);
        } else {
            DB::table('blog_config')->insert($config);
        }

        return jsonSuccess();
    }

    function getAboutMe()
    {
        $config = DB::table('pages')
            ->select('type', 'md', 'html')
            ->where('type','=', 'about')
            ->first();

        return jsonSuccess($config);
    }

    function modifyAbout()
    {
        $content = $this->request->post('aboutMeContent');
        $htmlContent = $this->request->post('htmlContent');

        $config = DB::table('pages')
            ->where('type','=', 'about')
            ->first();

        if ($config) {
            DB::table('pages')->where('id', $config->id)->update( array('md'=> $content, 'html'=> $htmlContent));
        } else {
            DB::table('pages')->insert(array('md'=> $content, 'html'=> $htmlContent, 'type'=> 'about'));
        }

        return jsonSuccess();
    }

    function getResume()
    {
        $config = DB::table('pages')
            ->select('type', 'md', 'html')
            ->where('type','=', 'resume')
            ->first();

        return jsonSuccess($config);
    }

    function modifyResume()
    {
        $content = $this->request->post('resumeContent');
        $htmlContent = $this->request->post('htmlContent');

        $config = DB::table('pages')
            ->where('type','=', 'resume')
            ->first();

        if ($config) {
            DB::table('pages')->where('id', $config->id)->update( array('md'=> $content, 'html'=> $htmlContent));
        } else {
            DB::table('pages')->insert(array('md'=> $content, 'html'=> $htmlContent, 'type'=> 'resume'));
        }

        return jsonSuccess();
    }
}
