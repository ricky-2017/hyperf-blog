<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use App\Constants\ReturnCode;
use App\Model\Comments;
use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * @Consumer(exchange="comment", routingKey="comment", queue="comment", name ="CommentConsumer", nums=1)
 */
class CommentConsumer extends ConsumerMessage
{
    public function consumeMessage($data, AMQPMessage $message): string
    {
        $insert = [
            'article_id'    => $data['articleId'],
            'name'          => $data['name'],
            'reply_id'      => $data['replyId'] ?? null,
            'content'       => $data['content'],
            'source_content'=> $data['sourceContent'],
            'email'         => $data['email'],
            'create_time'   => time(),
        ];

        if ($data['replyId'] == 0) {
            $insert['parent_id'] = 0;
        } else {
            // 检测回复的评论是否存在
            $comments = Comments::query()->where('id','=',$data['replyId'])->first();
            if ($comments['article_id'] != $data['articleId']) {
                bizException(ReturnCode::DATA_CONSTRAINT_ERROR,'文章与评论不匹配');
            }
            if ($comments['parent_id'] == 0) {
                $insert['parent_id'] = $comments['id'];
            } else {
                $insert['parent_id'] = $comments['parent_id'];
            }
        }

        Comments::query()->insert($insert);

        return Result::ACK;
    }
}
