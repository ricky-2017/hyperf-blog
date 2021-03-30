<?php
/**
 * Created by PhpStorm.
 * User: rubio
 * Date: 2021/3/29
 * Time: 11:17
 */

namespace App\Controller;

use Hyperf\Amqp\Producer;
use App\Amqp\Producer\DemoProducer;
use Hyperf\Utils\ApplicationContext;


class AqController extends AbstractController
{

    function testPush()
    {
        $message = new DemoProducer(['id' => 1,'content'=>'hello']);
        $producer = ApplicationContext::getContainer()->get(Producer::class);
        $result = $producer->produce($message);
    }

}