<?php

namespace app\controller;

use think\facade\Db;
use think\Request;
use think\facade\Validate;
use app\model\User as UserModel;
use app\validate\User as UserValidate;
use think\exception\Exception;
use think\exception\ValidateException;

class User extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = UserModel::field('id, name, password, email')
                -> page($this->page, $this->pageSize)
                -> select();
        
        if(!$data -> isEmpty()) {
            return $this->create($data, 'Request suceess', 200);
        } else {
            return $this->create($data, 'Resource not found', 204);
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 获取数据
        $data = $request->param();

        // 验证返回
        try {
            validate(UserValidate::class)->check($data);
        } catch (\Exception $e) {
            return $this->create([], $e -> getError(), 400);
        }

        // $id = UserModel::create($data)->getData('id');

        // if(empty($id)) {
        //     return $this->create([], 'Register failed', 400);
        // } else {
        //     return $this->create([], 'Register successfully', 200);
        // }

    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        if(!Validate::isInteger($id)) {
            return $this->create([], 'id params is not valid', 204);
        }

        $data = UserModel::field('id, name, password, email')->find($id);
        if(!empty($data)) {
            return $this->create($data, 'Request suceess', 200);
        } else {
            return $this->create($data, 'Resource not found', 204);
        };
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
        return "update";
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        return "delete";
    }


    // 登录
    public function login(Request $request) {
        $data = $request->param();
        $user_name = $data['userName'];
        $password = $data['password'];
        $data = Db::table('user')->where('username', $user_name)->select();
        if(count($data) == 0) {
            return $this->create([], 'User not found', 204);
        }
        $data = Db::table('user')
                ->field('id, username')
                ->where('username', $user_name)
                ->where('password', $password)
                ->select();

        $user = ['user' => $data];
        if(count($data) == 0) {
            return $this->create([], 'Password not correct', 205);
        }
        return $this->create($user, 'Login successfully', 200);
    }

    // 注册
    public function register(Request $request) {
        $data = $request->param();
        $user_name = $data['userName'];
        $password = $data['password'];
        $email = $data['email'];
        $result = Db::table('user')->where('username', $user_name)->select();
        $ins = [
            'username' => $user_name,
            'password' => $password,
            'email' => $email
        ];
        try {
            validate(UserValidate::class)->check($ins);
        } catch (ValidateException $e) {
            dump($e->getError());
        }
        if(count($result) != 0) {
            return $this->create([], 'User has existed', 204);
        }
        $userId = Db::name('user')->insertGetId($ins);
        if($userId) {
            return $this->create($userId, 'Register successfully', 200);
        } else {
            return $this->create([], 'Register failed', 204);
        }        
    }

    // 加入收藏
    public function addCollect(Request $request) {
        $user_id = $request->param('user_id');
        $product_id = $request->param('product_id');
        if($user_id == null || $product_id == null) {
            return $this->create([], 'Invalid params', 204);
        }
        $data = Db::table('collect')
                ->where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->select();
        if(count($data) != 0) {
            return  $this->create([], 'Collect has existed', 200);
        } else {
            $ins = [
                'user_id' => $user_id,
                'product_id' => $product_id,
            ];
            $collectId = Db::name('collect')->insertGetId($ins);
            return $this->create($collectId, 'Add collect successfully', 200);
        }
    }
    
    // 加入购物车
    public function addShoppingCart(Request $request) {
        $user_id = $request->param('user_id');
        $product_id = $request->param('product_id');
        if($user_id == null || $product_id == null) {
            return $this->create($result, 'Invalid params', 204);
        }
        $data = Db::table('shoppingCart')
                ->where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->select();
        $ins = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'num' => 1
        ];
        $code = 0;
        if(count($data) != 0) {
            Db::table('shoppingCart')
                ->where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->inc('num')
                ->update();
            $code = 201;
        } else {
            Db::table('shoppingCart')->insertGetId($ins);
            $code = 200;
        }
        $res1 = Db::table('shoppingCart')
                ->where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->find();
        $res2 = Db::table('product')
                    ->where('product_id', $product_id)
                    ->find();
        $data = [
            'user_id' => $res1['user_id'],
            'product_id' => $res1['product_id'],
            'num' => $res1['num'],
            'category_id' => $res2['category_id'],
            'product_name' => $res2['product_name'],
            'product_title' => $res2['product_title'],
            'product_intro' => $res2['product_intro'],
            'product_picture' => $res2['product_picture'],
            'product_price' => $res2['product_price'],
            'product_selling_price' => $res2['product_selling_price']
        ];
        return $this->create($data, 'Add shoppingCart successfully', $code);
    }

    // 更新购物车数量
    public function updateShoppingCart(Request $request) {
        $user_id = $request->param('user_id');
        $product_id = $request->param('product_id');
        $num = $request->param('num');
        if($user_id == null || $product_id == null || $num == null) {
            return $this->create([], 'Invalid params', 204);
        }
        Db::table('shoppingCart')
            ->where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->save(['num' => $num]);
        return $this->create([], 'Request successfully', 200);
    }

    // 获取收藏列表
    public function getCollectList(Request $request) {
        $user_id = $request->param('user_id');
        if($user_id == null) {
            return $this->create($result, 'Invalid params', 204);
        }
        $result = Db::table('collect')
                    ->where('user_id', $user_id)
                    ->select();
        $data = [];
        foreach ($result as $key => $value) {
            $product_id = $value['product_id'];
            $detail = Db::table('product')->where('product_id', $product_id)->find();
            Array_push($data, $detail);
        }
        return $this->create($data, 'Request successfully', 200);
    }

    // 获取购物车列表
    public function getShoppingCartList(Request $request) {
        $user_id = $request->param('user_id');
        if($user_id == null) {
            return $this->create($result, 'Invalid params', 204);
        }
        $result = Db::table('shoppingCart')
                    ->where('user_id', $user_id)
                    ->select();
        $data = [];
        foreach ($result as $key => $value) {
            $product_id = $value['product_id'];
            $detail = Db::table('product')->where('product_id', $product_id)->find();
            $detail['num'] = $value['num'];
            Array_push($data, $detail);
        }
        return $this->create($data, 'Request successfully', 200);
    }

    // 购买成功后，删除购物车中的内容，并加入订单
    public function deleteShoppingCart(Request $request) {
        $user_id = $request->param('user_id');
        $products = $request->param('products');
        $order_id = date('YmdHis');
        $order_time = time();
        if($user_id == null || $products == null) {
            return $this->create([], 'Invalid params', 204);
        }
        foreach ($products as $key => $value) {
            $product_id = $value['product_id'];
            $data = Db::table('shoppingCart')->where('user_id', $user_id)->where('product_id', $product_id)->find();
            Db::table('shoppingCart')->where('user_id', $user_id)->where('product_id', $product_id)->delete();
            $this->updateOrders($data, $order_id, $order_time);
        }
        return $this->create([], 'Delete successfully', 200);
    }

    // 删除收藏中的某一项
    public function deleteCollect(Request $request) {
        $user_id = $request->param('user_id');
        $product_id = $request->param('product_id');
        if($user_id == null || $product_id == null) {
            return $this->create([], 'Invalid params', 204);
        }
        Db::table('collect')->where('user_id', $user_id)->where('product_id', $product_id)->delete();
        return $this->create([], 'Delete successfully', 200);
    }

    // 删除购物车中的某一项
    public function deleteFromShoppingCart(Request $request) {
        $user_id = $request->param('user_id');
        $product_id = $request->param('product_id');
        if($user_id == null || $product_id == null) {
            return $this->create([], 'Invalid params', 204);
        }
        Db::table('shoppingCart')->where('user_id', $user_id)->where('product_id', $product_id)->delete();
        return $this->create([], 'Delete successfully', 200);
    }

    // 修改订单
    public function updateOrders($orderItem, $order_id, $order_time) {
        $user_id = $orderItem['user_id'];
        $product_id = $orderItem['product_id'];
        $product_num = $orderItem['num'];
        // $user_id = $request->param('user_id');
        // $product_id = $request->param('product_id');
        // $product_num = $request->param('product_num');
        $res = Db::table('product')
                        ->field('product_price')
                        ->where('product_id', $product_id)
                        ->find();
        $ins = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'order_id' => $order_id,
            'product_num' => $product_num,
            'product_price' => $res['product_price'],
            'order_time' => $order_time
        ];
        Db::table('orders')->insertGetId($ins);
    }

    // 获取订单数据
    public function getOrderList(Request $request) {
        $user_id = $request->param('user_id');
        $orderIDs = Db::table('orders')
                    ->distinct(true)
                    ->field('order_id')
                    ->where('user_id', $user_id)
                    ->select();
        $res = [];
        foreach ($orderIDs as $key => $item) {
           $order_id = $item['order_id'];
           $products = Db::table('orders')->where('order_id', $order_id)->select()->toArray();
            for($i = 0; $i < count($products); $i++) {
                $product_id = $products[$i]['product_id'];
                $detail = Db::table('product')->where('product_id', $product_id)->find();
                $products[$i]['product'] = $detail;
            }
           Array_push($res, $products);
        }
        
        return $this->create($res, 'Request successfully', 200);
    }
}
