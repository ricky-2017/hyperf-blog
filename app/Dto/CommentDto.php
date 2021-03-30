<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2021/3/30
 * Time: 13:58
 */

namespace App\Dto;

use App\Common\dto\BaseDto;

class CommentDto extends BaseDto
{
    private $articleId;
    private $name;
    private $replyId;
    private $content;
    private $sourceContent;
    private $email;

    /**
     * @return mixed
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param mixed $articleId
     */
    public function setArticleId($articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getReplyId()
    {
        return $this->replyId;
    }

    /**
     * @param mixed $replyId
     */
    public function setReplyId($replyId): void
    {
        $this->replyId = $replyId;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSourceContent()
    {
        return $this->sourceContent;
    }

    /**
     * @param mixed $sourceContent
     */
    public function setSourceContent($sourceContent): void
    {
        $this->sourceContent = $sourceContent;
    }
}