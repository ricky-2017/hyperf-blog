<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2020/8/22
 * Time: 13:24
 */

namespace App\Service;


interface ArticleService
{
    function list($search = [], $page, $pageSize);

    function archivesList($search = [], $page, $pageSize);

    function tags();

    function categories();

    function getArticle($id);

    function saveArticle($data);
}
