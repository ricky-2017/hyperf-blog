<?php

namespace App\Model;


use Hyperf\DbConnection\Db;

class BlogConfig extends Model
{
    protected $table = 'blog_config';
    protected $primaryKey = 'id';

    public function getWebConfig()
    {
        $config = self::query()->select('blog_name as blogName','avatar','sign','github')->first();
        return $config->toArray();
    }

    public function getQrCode(){
        $config = self::query()->select('wxpay_qrcode as wxpayQrcode','alipay_qrcode as alipayQrcode')->first();
        return $config->toArray();
    }

//
//    public function get_about_me()
//    {
//        $config = $this->db->from(TABLE_PAGES)
//            ->select('html')
//            ->where('type', 'about')
//            ->get()
//            ->row_array();
//        if (!$config || !isset($config['html'])) {
//            $config = array(
//                'html'=> ''
//            );
//        }
//
//        return success($config);
//    }
//
//    public function get_resume()
//    {
//        $config = $this->db->from(TABLE_PAGES)
//            ->select('html')
//            ->where('type', 'resume')
//            ->get()
//            ->row_array();
//        if (!$config || !isset($config['html'])) {
//            $config = array(
//                'html'=> ''
//            );
//        }
//
//        return success($config);
//    }


}
