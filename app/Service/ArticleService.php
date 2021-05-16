<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2020/8/22
 * Time: 13:24
 */

namespace App\Service;


use App\Dto\CommentDto;

interface ArticleService
{
    function list($page, $pageSize, $search = []);

    function archivesList($search = [], $page, $pageSize);

    function tags();

    function categories();

    function getArticle($id);

    function saveArticle($data);

    function addComment(CommentDto $commentDto);

    function getComment($article_id);
}
