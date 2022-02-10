<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    App\Service\ArticleService::class => App\Service\impl\ArticleServiceImpl::class,
    App\Service\System\ElementService::class => App\Service\System\impl\ElementServiceImpl::class,
    App\Service\System\RoleService::class => App\Service\System\impl\RoleServiceImpl::class,
    App\Service\System\RuleService::class => App\Service\System\impl\RuleServiceImpl::class,
    App\Service\System\SysGroupService::class => App\Service\System\impl\SysGroupServiceImpl::class,
    App\Service\System\UploadService::class => App\Service\System\impl\UploadServiceImpl::class,
    App\Service\System\UserService::class => App\Service\System\impl\UserServiceImpl::class
];
