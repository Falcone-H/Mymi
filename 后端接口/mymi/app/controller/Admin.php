<?php

namespace app\controller;

use think\Request;
use think\facade\Db;

class Admin extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return "admin index";
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        return "admin read";
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    // 获取商品数据
    public function getProductData(Request $request)
    {
        $current_page = $request->param('currentPage');
        $page_size = $request->param('pageSize');
        $product = Db::table('product')
            ->page($current_page, $page_size)
            ->select();
        $total = Db::table('product')->count();
        $data = [
            'product' => $product,
            'total' => $total
        ];
        return $this->create($data, 'Request successfully', 200);
    }

    // 获取订单数据
    public function getOrderData(Request $request)
    {
        $current_page = $request->param('currentPage');
        $page_size = $request->param('pageSize');
        $orderIDs = Db::table('orders')
            ->distinct(true)
            ->field('order_id, user_id')
            ->page($current_page, $page_size)
            ->select();
        $res = [];
        $total = Db::table('orders')->count();
        foreach ($orderIDs as $key => $item) {
            $order_id = $item['order_id'];
            $user_id = $item['user_id'];
            $username = Db::table('user')->field('username')->where('id', $user_id)->find();
            $products = Db::table('orders')->where('order_id', $order_id)->select()->toArray();
            $address = "菜鸟驿站";
            $state = "成功";
            for ($i = 0; $i < count($products); $i++) {
                $product_id = $products[$i]['product_id'];
                $detail = Db::table('product')->where('product_id', $product_id)->find();
                $products[$i]['product'] = $detail;
                $products[$i]['username'] = $username['username'];
                $products[$i]['address'] = $address;
                $products[$i]['state'] = $state;
                Array_push($res, $products[$i]);
            }
        }
        $res['total'] = $total;
        return $this->create($res, 'Request successfully', 200);
    }
}
