<?php

namespace App\Controller\System;

use App\Controller\AbstractController;
use App\Service\System\UserService;


class LoginController extends AbstractController
{

    public function login(UserService $userService)
    {
        $data = $this->request->all();
        $token = $userService->login($data['username'], $data['password']);
        return jsonSuccess( ["token" => $token]);
    }

    public function captcha(UserService $userService)
    {
        return jsonSuccess(['captcha_base64_img' => $userService->captcha()]);
    }
}
