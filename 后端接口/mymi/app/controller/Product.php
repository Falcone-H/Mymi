<?php

namespace app\controller;

use think\Request;
use think\facade\Db;
use app\model\Product as ProductModel;

class Product extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return "product index";
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
        return "product read";
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

    // 获取轮播图
    public function getCarousel() {
        $data = Db::table('carousel') -> select();
        if(!empty($data)) {
            return $this->create($data, 'Request successfully', 200);
        }
    }

    // 根据类别名获取类别ID
    public function getCategoryID($category_name) {
        $result = Db::table('category')->where('category_name', $category_name)->select();
        return $result[0]['category_id'];
    }

    // 根据类别名获取类别列表
    public function getCategory(Request $request) {
        $category_name = $request->param('category_name');
        if(empty($category_name)) {
            return $this->create([], 'category_name is empty', 204);
        }
        $data = [];
        foreach ($category_name as $key => $value) {
            $category_id = $this->getCategoryID($value);
            $res = Db::table('product')->where('category_id', $category_id)->select();
            foreach ($res as $k => $v) {
                Array_push($data, $v);
            }
        }
        return $this->create($data, 'Request successfully', 200);      
    }

    // 获取所有类别ID 和 类别名
    public function getCategoryInfo() {
        $result = Db::table('category')->select();
        return $this->create($result, 'Request successfully', 200);     
    }

    //  获取所有商品，并根据页数进行分页
    public function getAllProduct(Request $request) {
        $current_page = $request->param('currentPage');
        $page_size = $request->param('pageSize');
        $product = Db::table('product')
                    ->page($current_page, $page_size)
                    -> select();
        $total = Db::table('product')->count();
        $data = [
            'product' => $product,
            'total' => $total
        ];
        return $this->create($data, 'Request successfully', 200);     
    }

    // 根据商品ID获取商品
    public function getProductByID(Request $request) {
        $category_id = $request->param('categoryID');
        $current_page = $request->param('currentPage');
        $page_size = $request->param('pageSize');
        $product = Db::table('product')
                    ->where('category_id', $category_id)
                    ->page($current_page, $page_size)
                    -> select();
        $total = Db::table('product')->where('category_id', $category_id)->count();
        $data = [
            'product' => $product,
            'total' => $total
        ];
        return $this->create($data, 'Request successfully', 200);
    }

    // 获取商品详情
    public function getDetails(Request $request) {
        $product_id = $request->param('productID');
        $details = Db::table('product')->where('product_id', $product_id)->find();
        return $this->create($details, 'Request successfully', 200);
    }

    // 获取商品详情图片
    public function getDetialsPicture(Request $request) {
        $product_id = $request->param('productID');
        $details = Db::table('product_picture')->where('product_id', $product_id)->select();
        return $this->create($details, 'Request successfully', 200);
    }

    // 搜索商品
    public function getProductBySearch(Request $request) {
        $search = $request->param('search');
        $current_page = $request->param('currentPage');
        $page_size = $request->param('pageSize');
        $product = Db::table('product')
                    ->where('product_name', 'like', '%'.$search.'%')
                    ->page($current_page, $page_size)
                    ->select();
        return $this->create($product, 'Request successfully', 200);
    }

    public function getCommentByID(Request $request) {
        $product_id = $request->param('product_id');
        $comment = Db::table('comment')->where('product_id', $product_id)->select();
        return $this->create($comment, 'Request successfully', 200);
    }
}
