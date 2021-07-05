<template>
  <div id="myList" class="myList">
    <ul>
      <li v-for="item in (isMore?showList:list)" :key="item.product_id">
        <i class="el-icon-close delete" v-show="isDelete" @click="toDelete(item.product_id)"></i>
        <router-link :to="{ path: '/goods/details', query: {productID:item.product_id} }">
          <img :src="$target + item.product_picture" />
          <h2>{{item.product_name}}</h2>
          <h3>{{item.product_title}}</h3>
          <p>
            <span>{{item.product_selling_price}}元</span>
            <span v-show="item.product_price != item.product_selling_price" class="del">{{item.product_price}}元</span>
          </p>
        </router-link>
      </li>
      <li v-show="isMore && list.length>=1" id="more">
        <router-link :to="{ path: '/goods', query: {categoryID:categoryID} }">
          浏览更多
          <i class="el-icon-d-arrow-right"></i>
        </router-link>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  name: "MyList",
  props: ["list", "isMore", "isDelete"],
  data() {
    return {
      mlist: this.list,
      showList: []
    };
  },
  beforeUpdate() {
    this.showList = Array.from(this.list);
    if (this.isMore === true && this.showList.length > 7) {
      this.showList = this.showList.splice(0, 7);
    }
  },
  computed: {
    // 通过list获取当前显示的商品的分类ID，用于“浏览更多”链接的参数
    categoryID: function () {
      let categoryID = [];
      if (this.mlist != "") {
        for (let i = 0; i < this.mlist.length; i++) {
          const id = this.mlist[i].category_id;
          if (!categoryID.includes(id)) {
            categoryID.push(id);
          }
        }
      }
      return categoryID;
    }
  },
  methods: {
    deleteCollect(product_id) {
      this.$axios
        .post("/api/user/deleteCollect", {
          user_id: this.$store.getters.getUser.id,
          product_id: product_id
        })
        .then(res => {
          switch (res.data.code) {
            case 200:
              // 删除成功
              // 删除删除列表中的该商品信息
              for (let i = 0; i < this.mlist.length; i++) {
                const temp = this.mlist[i];
                if (temp.product_id == product_id) {
                  this.mlist.splice(i, 1);
                }
              }
              // 提示删除成功信息
              this.notifySucceed(res.data.msg);
              break;
            default:
              // 提示删除失败信息
              this.notifyError(res.data.msg);
          }
        })
        .catch(err => {
          return Promise.reject(err);
        });
    },
    toDelete(product_id) {
      this.$confirm('此操作将删除该收藏, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.deleteCollect(product_id);
        this.$message({
          type: 'success',
          message: '删除成功!'
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        });
      });
    }
  }
};
</script>
<style scoped>
.myList ul li {
  z-index: 1;
  float: left;
  width: 234px;
  height: 280px;
  padding: 10px 0;
  margin: 0 0 14.5px 13.7px;
  background-color: white;
  -webkit-transition: all 0.2s linear;
  transition: all 0.2s linear;
  position: relative;
}
.myList ul li:hover {
  z-index: 2;
  -webkit-box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  -webkit-transform: translate3d(0, -2px, 0);
  transform: translate3d(0, -2px, 0);
}
.myList ul li img {
  display: block;
  width: 160px;
  height: 160px;
  background: url(../assets/imgs/placeholder.png) no-repeat 50%;
  margin: 0 auto;
}
.myList ul li h2 {
  margin: 25px 10px 0;
  font-size: 14px;
  font-weight: 400;
  color: #333;
  text-align: center;
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
}
.myList ul li h3 {
  margin: 5px 10px;
  height: 18px;
  font-size: 12px;
  font-weight: 400;
  color: #b0b0b0;
  text-align: center;
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
}
.myList ul li p {
  margin: 10px 10px 10px;
  text-align: center;
  color: #ff6700;
}
.myList ul li p .del {
  margin-left: 0.5em;
  color: #b0b0b0;
  text-decoration: line-through;
}
.myList #more {
  text-align: center;
  line-height: 280px;
}
.myList #more a {
  font-size: 18px;
  color: #333;
}
.myList #more a:hover {
  color: #ff6700;
}
.myList ul li .delete {
  position: absolute;
  top: 10px;
  right: 10px;
  display: none;
}
.myList ul li:hover .delete {
  display: block;
}
.myList ul li .delete:hover {
  color: #ff6700;
}
</style>