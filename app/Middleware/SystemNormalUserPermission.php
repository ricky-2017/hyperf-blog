<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/12
 * Time: 15:56
 */

namespace App\Middleware;

use App\Constants\ReturnCode;
use App\Model\SysLog;
use App\Service\System\UserService;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SystemNormalUserPermission implements MiddlewareInterface
{
    private $userService;
    private $sysLog;
    public $sysLogId; // 日志id

    public function __construct(UserService $userService, SysLog $sysLog)
    {
        $this->userService = $userService;
        $this->sysLog = $sysLog;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);

        Context::set(ResponseInterface::class, $response);

        $user_id = Context::get('user_id');
        if (empty($user_id)) {
            bizException(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND, "用户标识为空，token无效123123");
        }

        $user = $this->userService->get($user_id);
        if (empty($user)) {
            bizException(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND, "用户不存在，token无效");
        }

        //        $token = (new Parser())->parse((string)request()->token);
//        if (!empty($user['last_update_password_time'])
//            && strtotime($user['last_update_password_time']) > $token->getClaim("iat")) {// 更新了密码，token废弃
//            bizException(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND, "token已过期，请重新登录");
//        }

        if ($user['user_status'] != 1) {
            bizException(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND, "账号已被禁用");
        }

        Context::set('sys_user_id', $user_id);
        Context::set('admin_info', [
            'sys_user_id' => $user_id,
            'user_group' => $user->user_group,
            'user_name' => $user->user_name
        ]);
        Context::set('user_group', $user->user_group);

//        $this->checkPermission($request);

        // 写入日志 返回主键ID 为其他特殊场景  adminType = 1 则为超级管理员
//        $this->sysLogId = $this->sysLog->record($user, 1);
//        if ($this->sysLogId) {
//            $request->sysLogId = $this->sysLogId;
//        }

        return $handler->handle($request);
    }


    /**
     * api鉴权
     * @param $request
     * @throws
     */
//    private function checkPermission($request)
//    {
//        // 开发者管理员拥有所有权限
//        if ($request->admin_info['user_name'] == config('service.SUPER_ADMIN')
//            && $request->header()['mofeng'] == 'keji') {
//            return true;
//        }
//
//        $roleIds = app(UserRole::class)->where('user_id', $request->sys_user_id)->column('role_id');
//        $ruleIds = app(RoleRule::class)->where('role_id', 'in', $roleIds)->column('rule_id');
//        $elementIds = app(RuleResource::class)->where('rule_id', 'in', $ruleIds)->where('resource_type', 'ELEMENT')->column('resource_id');
//        $apiPoints = app(ElementApi::class)->where('sys_element_id', 'in', $elementIds)->column('api_endpoint');
//
//
//        $pass = [
//            'system/users/getMyUser',
//            'system/elements/listMyTree'
//        ];
//        if(!in_array($request->pathinfo(), $pass)){
//            if (!in_array($request->pathinfo(), $apiPoints)) {
//                bizException(ReturnCode::AUTHENTICATION_FAILED, "您无权访问该接口");
//            }
//        }
//    }
}