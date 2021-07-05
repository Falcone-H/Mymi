<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Comment extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username|用户名' => ['require', 'regex'=>'/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/'],
        'password|密码' => ['require', 'regex'=>'/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/'],
        'email|邮箱' => ['require', 'email'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];
}
