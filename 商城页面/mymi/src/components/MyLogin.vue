<template>
  <div id="myLogin">
    <el-dialog title="登录" width="400px" center v-model="isLogin">
      <el-form :model="LoginUser" :rules="rules" status-icon ref="ruleForm" class="demo-ruleForm">
        <el-form-item prop="name">
          <el-input prefix-icon="el-icon-user-solid" placeholder="请输入用户名" v-model="LoginUser.name"></el-input>
        </el-form-item>
        <el-form-item prop="pass">
          <el-input
            prefix-icon="el-icon-view"
            type="password"
            placeholder="请输入密码"
            v-model="LoginUser.pass"
          ></el-input>
        </el-form-item>
        <el-form-item>
          <mi-captcha width="350px" theme-color="#2db7f5" :onCaptchaInit="onSuccess"></mi-captcha>
        </el-form-item>
        <el-form-item>
          <el-button
            size="medium"
            type="primary"
            @click="Login"
            @keyup.enter="Login"
            style="width:100%;"
          >登录</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
import { mapActions } from "vuex";
export default {
  name: "MyLogin",
  data() {
    let validateName = (rule, value, callback) => {
      // 用户名校验
      if (!value) {
        return callback(new Error("请输入用户名"));
      }
      // 用户名以字母开头，长度在5-16之间，允许字母数字下划线
      const userNameRule = /^[a-zA-Z][a-zA-Z0-9_]{4,15}$/;
      if (userNameRule.test(value)) {
        this.$refs.ruleForm.validateField("checkPass");
        return callback();
      } else {
        return callback(new Error("字母开头,长度5-16之间,允许字母数字下划线"));
      }
    };
    let validatePass = (rule, value, callback) => {
      // 密码校验方法
      if (value === "") {
        return callback(new Error("请输入密码"));
      }
      // 密码以字母开头,长度在6-18之间,允许字母数字和下划线
      const passwordRule = /^[a-zA-Z]\w{5,17}$/;
      if (passwordRule.test(value)) {
        this.$refs.ruleForm.validateField("checkPadd");
        return callback();
      } else {
        return callback(
          new Error("字母开头,长度6-18之间,允许字母数字和下划线")
        );
      }
    };
    return {
      LoginUser: {
        name: "",
        pass: "",
      },
      // 用户信息校验规则，validator(校验方法)，trigger(触发方式)，blur为组件在市区焦点时触发
      rules: {
        name: [{ validator: validateName, trigger: "blur" }],
        pass: [{ validator: validatePass, trigger: "blur" }],
      },
      slided: false,
    };
  },
  computed: {
    // 获取 vuex 中的 showLogin，控制登录组件是否显示
    isLogin: {
      get() {
        return this.$store.getters.getShowLogin;
      },
      set(val) {
        this.$refs["ruleForm"].resetFields();
        this.setShowLogin(val);
      },
    },
  },
  methods: {
    ...mapActions(["setUser", "setShowLogin"]),
    Login() {
      this.$refs["ruleForm"].validate((valid) => {
        // 如果通过校验就开始登录
        if (valid) {
          this.$axios
            .post("/api/user/login", {
              userName: this.LoginUser.name,
              password: this.LoginUser.pass,
            })
            .then((res) => {
              if (res.data.code === 200) {
                // 登录成功
                this.isLogin = false;
                let user = JSON.stringify(res.data.data.user[0]);
                console.log(user);
                localStorage.setItem("user", user);
                this.setUser(res.data.user);
                this.notifySucceed(res.data.msg);
                location.reload();
              } else {
                // 登录失败
                this.$refs["ruleForm"].resetFields();
                this.notifyError(res.data.msg);
              }
            })
            .catch((err) => {
              return Promise.reject(err);
            });
        } else {
          return false;
        }
      });
    },
    onSuccess() {
      this.slided = true;
    },
  },
};
</script>
<style scoped>
</style>
