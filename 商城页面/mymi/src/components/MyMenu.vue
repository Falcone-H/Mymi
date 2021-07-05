<template>
  <div class="myMenu" id="myMenu">
    <ul>
      <li v-for="item in val" :key="item" :class="activeClass == item ? 'active':''"
        @mouseover="mouseover($event,item)">
        <router-link to>
          <slot :name="item"></slot>
        </router-link>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  name: 'MyMenu',
  props: ["val"],
  data() {
    return {
      activeClass: 1
    };
  },
  methods: {
    mouseover(e, val) {
      // 通过 mouseover 控制当前显示的商品分类
      this.activeClass = val;
    }
  },
  watch: {
    activeClass(val) {
      this.$emit("fromChild", val);
    }
  }
}
</script>
<style scoped>
#myMenu li {
  float: left;
  margin-left: 30px;
}

#myMenu a:hover {
  color: #ff6700;
  border-bottom: 2px solid #ff6700;
}

#myMenu .active a {
  color: #ff6700;
  border-bottom: 2px solid #ff6700;
}
</style>
