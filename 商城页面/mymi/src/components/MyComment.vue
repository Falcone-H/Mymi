<template>
  <div class="comment-box">
    <el-row>
      <el-col :span="2"></el-col>
      <el-col :span="20">
        <div class="title">评论</div>
        <div>
          <el-card class="box-card" style="100%" v-for="(item, index) in commentList" :key="index">
            <template #header>
              <div class="card-header">
                <i class="el-icon-user-solid" style="font-size: 30px"></i>
                <span class="username">{{item.username}}</span>
                <span class="post-time">{{dateFormat(item.comment_time)}}</span>
              </div>
            </template>
            <div class="card-rate">
              <el-rate
                v-model="item.rate"
                disabled
                show-score
                text-color="#ff9900"
                score-template="{value}"
              ></el-rate>
            </div>
            <div class="card-content">{{item.comment_msg}}</div>
          </el-card>
        </div>
        <div style="margin-top: 10px;">
          <el-button type="primary" @click="handlePost">
            发表评论
            <i class="el-icon-s-promotion" style="font-size: 15px"></i>
          </el-button>
          <el-dialog title="发表评论" v-model="dialogFormVisible">
            <el-form :model="form" :rules="rules">
              <el-form-item label="评论信息" :label-width="formLabelWidth" prop="comment">
                <el-input v-model="form.comment" autocomplete="off"></el-input>
              </el-form-item>
              <el-form-item label="评分" :label-width="formLabelWidth" prop="rate">
                <el-rate v-model="form.value" style="margin-top: 10px"></el-rate>
              </el-form-item>
            </el-form>
            <template #footer>
              <span class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="handleConfirm">确 定</el-button>
              </span>
            </template>
          </el-dialog>
        </div>
      </el-col>
      <el-col :span="2"></el-col>
    </el-row>
  </div>
</template>
<script>
export default {
  name: "MyComment",
  props: ["commentList"],
  data() {
    return {
      value: 3.7,
      dialogFormVisible: false,
      formLabelWidth: "80px",
      showList: [],
      form: {
        comment: "",
        value: 0,
      },
      rules: {
        comment: [{ required: true, message: "请输入评论", trigger: "blur" }],
        rate: [{ required: true, message: "请选择评分", trigger: "blur" }],
      },
    };
  },
  methods: {
    handlePost() {
      // 判断是否登录,没有登录则显示登录组件
      if (!this.$store.getters.getUser) {
        this.$store.dispatch("setShowLogin", true);
        return;
      }
      this.dialogFormVisible = true;
    },
    handleConfirm() {
      if (this.form.comment === "") {
        this.$alert("请输入评论", "提示", {
          confirmButtonText: "确定",
        });
        return;
      }
      if (this.form.value === 0) {
        this.$alert("请选择评分", "提示", {
          confirmButtonText: "确定",
        });
        return;
      }
      let val = {
        product_id: 1,
        username: this.$store.state.user.user.username,
        comment_time: parseInt(new Date() / 1000),
        comment_msg: this.form.comment,
        rate: this.form.value,
      };
      this.commentChange(val);
      this.dialogFormVisible = false;
      this.form.comment = "";
      this.form.value = 0;
    },
    handleCencel() {
      this.dialogFormVisible = false;
      this.form.comment = "";
      this.form.value = 0;
    },
    dateFormat(timeStamp) {
      var date = new Date(timeStamp * 1000);
      let y = date.getFullYear();
      let MM = date.getMonth() + 1;
      MM = MM < 10 ? "0" + MM : MM;
      let d = date.getDate();
      d = d < 10 ? "0" + d : d;
      let h = date.getHours();
      h = h < 10 ? "0" + h : h;
      let m = date.getMinutes();
      m = m < 10 ? "0" + m : m;
      let s = date.getSeconds();
      s = s < 10 ? "0" + s : s;
      return y + "-" + MM + "-" + d + " " + h + ":" + m + ":" + s;
    },
    commentChange(val) {
      this.$emit("commentChange", val);
    },
  },
};
</script>
<style scoped>
.title {
  font-size: 22px;
  font-weight: 200;
  line-height: 58px;
  color: rgb(24, 23, 23);
}
.comment-box {
  width: 100%;
}

.box-card {
  margin-top: 10px;
}

.card-header {
  position: relative;
  width: 100%;
}

.card-content {
  margin-top: 10px;
}

.post-time {
  position: absolute;
  right: 0%;
}

.username {
  position: relative;
  left: 1%;
}
</style>
