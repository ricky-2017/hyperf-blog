# Introduction

- 感谢codebear兄弟的开源博客，[博客前端来源](http://codebear.cn/?ADTAG=gh)
- 个人在CI的框架的基础上，使用了hyperf框架进行了重构，数据表稍作调整但结构改动不大。
- hyperf版本
- [前端仓库地址 hyperf-master分支](https://gitee.com/rubio9/vueBlog/tree/hyperf-master/)
- [API仓库地址](https://github.com/ricky-2017/hyperf-blog)

### 上传文件模块可选择七牛云或者本地上传，具体配置在config/autoload/file.php 文件中进行配置

- 开发阶段可以使用 php bin/hyperf.php server:watch 进行热更新监控
- 具体配置项看.env.example文件 

### 完成进度
- [x] 首页（文章列表）
- [x] 分类/标签列表
- [x] 文章归档
- [x] ‘关于’页面
- [x] 文章详情页
- [x] 分类/标签 对应的文章列表
- [x] 搜索功能（按文章标题和简介搜索）
- [x] 文章详情页标题目录导航
- [x] 简历页

### 博客后台管理实现功能
- [x] 登录
- [x] 发布/编辑/删除文章
- [x] 添加/编辑/删除分类
- [x] 添加/编辑/删除标签
- [x] 添加/编辑/删除友链
- [x] 编辑‘关于’页面
- [x] 编辑博客配置页面（头像、昵称-等）
- [x] 管理评论
- [x] 简历编辑

# Requirements

Hyperf has some requirements for the system environment, it can only run under Linux and Mac environment, but due to the development of Docker virtualization technology, Docker for Windows can also be used as the running environment under Windows.

The various versions of Dockerfile have been prepared for you in the [hyperf\hyperf-docker](https://github.com/hyperf/hyperf-docker) project, or directly based on the already built [hyperf\hyperf](https://hub.docker.com/r/hyperf/hyperf) Image to run.

When you don't want to use Docker as the basis for your running environment, you need to make sure that your operating environment meets the following requirements:  

 - PHP >= 7.2
 - Swoole PHP extension >= 4.4，and Disabled `Short Name`
 - OpenSSL PHP extension
 - JSON PHP extension
 - PDO PHP extension （If you need to use MySQL Client）
 - Redis PHP extension （If you need to use Redis Client）
 - Protobuf PHP extension （If you need to use gRPC Server of Client）

# Installation using Composer

The easiest way to create a new Hyperf project is to use Composer. If you don't have it already installed, then please install as per the documentation.

To create your new Hyperf project:

$ composer create-project hyperf/hyperf-skeleton path/to/install

Once installed, you can run the server immediately using the command below.

$ cd path/to/install
$ php bin/hyperf.php start

This will start the cli-server on port `9501`, and bind it to all network interfaces. You can then visit the site at `http://localhost:9501/`

which will bring up Hyperf default home page.



