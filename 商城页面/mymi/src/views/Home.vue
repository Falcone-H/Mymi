<template>
  <div class="home" id="home" name="home">
    <!-- 轮播图 -->
    <div class="block">
      <el-carousel height="460px">
        <el-carousel-item v-for="item in carousel" :key="item.id">
          <img style="height:460px;" :src="$target + item.img_url" />
        </el-carousel-item>
      </el-carousel>
    </div>
    <!-- 轮播图END -->
    <div class="main-box">
      <div class="main">
        <!-- 手机商品展示区域 -->
        <div class="phone">
          <div class="box-hd">
            <div class="title">手机</div>
          </div>
          <div class="box-bd">
            <div class="promo-list">
              <router-link to>
                <img :src="$target +'imgs/phone/phone.png'" />
              </router-link>
            </div>
            <div class="list">
              <MyList :list="phoneList" :isMore="true"></MyList>
            </div>
          </div>
        </div>
        <!-- 手机商品展示区域END -->

        <!-- 家电商品展示区域 -->
        <div class="appliance" id="promo-menu">
          <div class="box-hd">
            <div class="title">家电</div>
            <div class="more" id="more">
              <MyMenu :val="2" @fromChild="getChildMsg">
                <template v-slot:1>
                  <span>热门</span>
                </template>
                <template v-slot:2>
                  <span>电视影音</span>
                </template>
              </MyMenu>
            </div>
          </div>
          <div class="box-bd">
            <div class="promo-list">
              <ul>
                <li>
                  <img :src="$target +'imgs/appliance/appliance-promo1.png'" />
                </li>
                <li>
                  <img :src="$target +'imgs/appliance/appliance-promo2.png'" />
                </li>
              </ul>
            </div>
            <div class="list">
              <MyList :list="applianceList" :isMore="true"></MyList>
            </div>
          </div>
        </div>
        <!-- 家电商品展示区域END -->

        <!-- 配件商品展示区域 -->
        <div class="accessory" id="promo-menu">
          <div class="box-hd">
            <div class="title">配件</div>
            <div class="more" id="more">
              <MyMenu :val="3" @fromChild="getChildMsg2">
                <template v-slot:1>
                  <span>热门</span>
                </template>
                <template v-slot:2>
                  <span>保护套</span>
                </template>
                <template v-slot:3>
                  <span>充电器</span>
                </template>
              </MyMenu>
            </div>
          </div>
          <div class="box-bd">
            <div class="promo-list">
              <ul>
                <li>
                  <img :src="$target +'imgs/accessory/accessory-promo1.png'" alt />
                </li>
                <li>
                  <img :src="$target +'imgs/accessory/accessory-promo2.png'" alt />
                </li>
              </ul>
            </div>
            <div class="list">
              <MyList :list="accessoryList" :isMore="true"></MyList>
            </div>
          </div>
        </div>
        <!-- 配件商品展示区域END -->
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      carousel: [{ id: 1, img_url: "imgs/carousel/1.webp" }], // 轮播图数据
      phoneList: [], // 手机商品列表
      miTvList: [], // 小米电视商品列表
      applianceList: [], // 家电商品列表
      applianceHotList: [], //热门家电商品列表
      accessoryList: [], //配件商品列表
      accessoryHotList: "", //热门配件商品列表
      protectingShellList: [], // 保护套商品列表
      chargerList: [], //充电器商品列表
      applianceActive: 1, // 家电当前选中的商品分类
      accessoryActive: 1, // 配件当前选中的商品分类
    };
  },
  watch: {
    applianceActive(val) {
      // console.log("hot", this.applianceHotList);
      // console.log("not hot", this.applianceList);
      // 家电当前选中的商品分类，响应不同的商品数据
      if (this.applianceHotList.length === 0) {
        this.applianceHotList = this.applianceList;
        console.log(0);
      }
      if (val == 1) {
        // 1 为热门商品
        this.applianceList = this.applianceHotList;
        console.log(1);
        return;
      }
      if (val == 2) {
        // 2 为电视商品
        this.applianceList = this.miTvList;
        console.log(2);
        return;
      }
    },
    accessoryActive(val) {
      if (this.accessoryHotList.length === 0) {
        this.accessoryHotList = this.accessoryList;
      }
      if (val == 1) {
        // 1 为热门商品
        this.accessoryList = this.accessoryHotList;
        return;
      }
      if (val == 2) {
        // 2 为保护套商品
        this.accessoryList = this.protectingShellList;
        return;
      }
      if (val == 3) {
        // 3 为保护套商品
        this.accessoryList = this.chargerList;
        return;
      }
    },
  },
  created() {
    this.getCarousel();
    this.getPromo(["手机"], "phoneList");
    this.getPromo(["电视机"], "miTvList");
    this.getPromo(["保护套"], "protectingShellList");
    this.getPromo(["充电器"], "chargerList");
    this.getPromo(["空调", "洗衣机", "电视机"], "applianceList");
    this.getPromo(["充电宝", "保护膜", "保护套", "充电器"], "accessoryList");
  },
  methods: {
    getChildMsg(val) {
      // 获取家电模块子组件传过来的数据
      this.applianceActive = val;
    },
    getChildMsg2(val) {
      // 获取配件模块子组件传过来的数据
      this.accessoryActive = val;
    },
    getCarousel() {
      // 获取轮播图数据
      this.$axios
        .get("/api/product/carousel")
        .then((res) => {
          this.carousel = res.data.data;
          console.log(this.carousel);
        })
        .catch((err) => {
          return Promise.reject(err);
        });
    },
    getPromo(category_name, val, api) {
      api = "api/product/category";
      this.$axios
        .post(api, {
          category_name,
        })
        .then((res) => {
          this[val] = res.data.data;
        })
        .catch((err) => {
          return Promise.reject(err);
        });
    },
  },
};
</script>
<style scoped>
@import "../assets/css/index.css";
</style>
