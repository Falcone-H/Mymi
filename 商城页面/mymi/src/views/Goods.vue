<template>
  <div class="goods" id="goods" name="goods">
    <!-- 面包屑 -->
    <div class="breadcrumb">
      <el-breadcrumb separator-class="el-icon-arrow-right">
        <el-breadcrumb-item to="/">首页</el-breadcrumb-item>
        <el-breadcrumb-item>全部商品</el-breadcrumb-item>
        <el-breadcrumb-item v-if="search">搜索</el-breadcrumb-item>
        <el-breadcrumb-item v-else>分类</el-breadcrumb-item>
        <el-breadcrumb-item v-if="search">{{search}}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>

    <!-- 分类标签 -->
    <div class="nav">
      <div class="product-nav">
        <div class="title">分类</div>
        <el-tabs v-model="activeName" type="card">
          <el-tab-pane
            v-for="item in categoryList"
            :key="item.category_id"
            :label="item.category_name"
            :name="''+item.category_id"
          />
        </el-tabs>
      </div>
    </div>

    <!-- 主要内容区 -->
    <div class="main">
      <div class="list">
        <MyList :list="product" v-if="product.length>0"></MyList>
        <div v-else class="none-product">抱歉没有找到相关的商品，请看看其他的商品</div>
      </div>
      <!-- 分页 -->
      <div class="pagination">
        <el-pagination
          background
          layout="prev, pager, next"
          :page-size="pageSize"
          :total="total"
          @current-change="currentChange"
        ></el-pagination>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      categoryList: "", // 分类列表
      categoryID: [], // 分类ID
      product: "", // 商品列表
      productList: "",
      total: 0, // 商品总量
      pageSize: 15, // 每页显示的商品数量
      currentPage: 1, // 当前页码
      activeName: "-1", // 分类列表当前选中的ID
      search: "", // 搜索条件
    };
  },
  created() {
    this.getCategory();
  },
  activated() {
    this.activeName = "-1";
    this.total = 0;
    this.currentPage = 1;
    if (Object.keys(this.$route.query).length == 0) {
      // 如果路由没有传递参数，默认为显示全部商品
      this.categoryID = [];
      this.activeName = "0";
      return;
    }
    if (this.$route.query.categoryID != undefined) {
      // 如果路由传递了商品ID，则显示对应的分类商品
      this.categoryID = this.$route.query.categoryID;
      if (this.categoryID.length == 1) {
        this.activeName = "" + this.categoryID[0];
      }
      return;
    }
    if (this.$route.query.search != undefined) {
      // 如果路由传递了 search，则为搜索，显示对应的分类商品
    }
  },
  watch: {
    activeName(val) {
      // 监听点击了哪个分类标签
      if (val == 0) {
        this.categoryID = [];
      }
      if (val > 0) {
        this.categoryID = [Number(val)];
      }
      this.total = 0;
      this.currentPage = 1;
      this.$router.push({
        path: "/goods",
        query: { categoryID: this.categoryID },
      });
    },
    search(val) {
      // 监听搜索条件
      if (val != "") {
        this.getProductBySearch(val);
      }
    },
    categoryID() {
      this.getData();
      this.search = "";
    },
    $route(val) {
      // 监听路由变化，更新路由传递的搜索条件
      if (val.path == "/goods") {
        if (val.query.search != undefined) {
          this.activeName = "-1";
          this.currentPage = 1;
          this.total = 0;
          this.search = val.query.search;
        }
      }
    },
  },
  methods: {
    backtop() {
      // 返回顶部
      const timer = setInterval(() => {
        const top =
          document.documentElement.scrollTop || document.body.scrollTop;
        const speed = Math.floor(-top / 5);
        document.documentElement.scrollTop = document.body.scrollTop =
          top + speed;

        if (top === 0) {
          clearInterval(timer);
        }
      }, 20);
    },
    currentChange(currentPage) {
      // 页码变化调用 currentChange 方法
      this.currentPage = currentPage;
      if (this.search != "") {
        this.getProductBySearch();
      } else {
        this.getData();
      }
      this.backtop();
    },
    getCategory() {
      // 向后端请求分类列表数据
      this.$axios
        .post("/api/product/categoryinfo", {})
        .then((res) => {
          const val = {
            category_id: 0,
            category_name: "全部",
          };
          const cate = res.data.data;
          cate.unshift(val);
          this.categoryList = cate;
        })
        .catch((err) => {
          return Promise.reject(err);
        });
    },
    getData() {
      // 如果分类列表为空，则请求全部商品数据
      // 否则，请求分类商品数据
      const api =
        this.categoryID.length == 0
          ? "/api/product/getAllProduct"
          : "/api/product/getProductByID";
      this.$axios
        .post(api, {
          categoryID: this.categoryID[0],
          currentPage: this.currentPage,
          pageSize: this.pageSize,
        })
        .then((res) => {
          this.product = res.data.data.product;
          this.total = res.data.data.total;
        })
        .catch((err) => {
          return Promise.reject(err);
        });
    },
    getProductBySearch() {
      this.$axios
        .post("/api/product/getProductBySearch", {
          search: this.search,
          currentPage: this.currentPage == null ? 1 : this.currentPage,
          pageSize: this.pageSize,
        })
        .then((res) => {
          this.product = res.data.data;
          this.total = res.data.data.length;
          console.log(this.product);
        })
        .catch((err) => {
          return Promise.reject(err);
        });
    },
  },
};
</script>
<style scoped>
.goods {
  background-color: #f5f5f5;
}

.el-tabs--card .el-tabs__header {
  border-bottom: none;
}
.goods .breadcrumb {
  height: 50px;
  background-color: white;
}
.goods .breadcrumb .el-breadcrumb {
  width: 1225px;
  line-height: 30px;
  font-size: 16px;
  margin: 0 auto;
}

.goods .nav {
  background-color: white;
}
.goods .nav .product-nav {
  width: 1225px;
  height: 40px;
  line-height: 40px;
  margin: 0 auto;
}
.nav .product-nav .title {
  width: 50px;
  font-size: 16px;
  font-weight: 700;
  float: left;
}

.goods .main {
  margin: 0 auto;
  max-width: 1225px;
}
.goods .main .list {
  min-height: 650px;
  padding-top: 14.5px;
  margin-left: -13.7px;
  overflow: auto;
}
.goods .main .pagination {
  height: 50px;
  text-align: center;
}
.goods .main .none-product {
  color: #333;
  margin-left: 13.7px;
}
</style>
