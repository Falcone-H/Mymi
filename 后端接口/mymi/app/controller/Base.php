<?php
namespace app\controller;
use think\Response;
use think\facade\Request;

abstract class Base
{

    protected $page;
    protected $pageSize;

    public function __construct() {
        $this->page = (int)Request::param('page', 1);
        $this->pageSize = (int)Request::param('page_size', 2);
    }

    protected function create($data, string $msg = '', int $code = 200) {
        // 标准 API 结构生成
        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];

        // 返回 API 接口
        return json($result);
    }

}
