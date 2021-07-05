<template>
  <div id="register">
    <el-dialog title="注册" width="400px" center v-model="isRegister">
      <el-form :model="RegisterUser" :rules="rules" status-icon ref="ruleForm" class="demo-ruleForm">
        <el-form-item prop="name">
          <el-input prefix-icon="el-icon-user-solid" placeholder="请输入用户名" v-model="RegisterUser.name"></el-input>
        </el-form-item>
        <el-form-item prop="email">
          <el-input prefix-icon="el-icon-message" type="email" placeholder="请输入邮箱" v-model="RegisterUser.email">
          </el-input>
        </el-form-item>
        <el-form-item prop="pass">
          <el-input prefix-icon="el-icon-view" type="password" placeholder="请输入密码" v-model="RegisterUser.pass">
          </el-input>
        </el-form-item>
        <el-form-item prop="confirmPass">
          <el-input prefix-icon="el-icon-view" type="password" placeholder="请再次输入密码" v-model="RegisterUser.confirmPass">
          </el-input>
        </el-form-item>
        <el-form-item>
          <el-button size="medium" type="primary" @click="Register" style="width:100%;">注册</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: 'MyRegister',
  props: ["register"],
  data() {
    let validateName = (rule, value, callback) => {
      // 用户名校验
      if (!value) {
        return callback(new Error("请输入用户名"));
      }
      // 用户名以字母开头,长度在5-16之间,允许字母数字下划线
      const userNameRule = /^[a-zA-Z][a-zA-Z0-9_]{4,15}$/;
      if (userNameRule.test(value)) {
        this.$refs.ruleForm.validateField("checkPass");
        return callback();
      } else {
        return callback(new Error("字母开头,长度5-16之间,允许字母数字下划线"));
      }
    };
    let validateEmail = (rule, value, callback) => {
      // 邮箱校验
      if (value === "") {
        return callback(new Error("请输入邮箱"));
      }
      const emailRule = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
      if (emailRule.test(value)) {
        this.$refs.ruleForm.validateField("checkPass");
        return callback();
      } else {
        return callback(new Error('请输入合法的邮箱'));
      }
    };
    let validatePass = (rule, value, callback) => {
      // 密码校验
      if (value === "") {
        return callback(new Error("请输入密码"));
      }
      // 密码以字母开头,长度在6-18之间,允许字母数字和下划线
      const passwordRule = /^[a-zA-Z]\w{5,17}$/;
      if (passwordRule.test(value)) {
        this.$refs.ruleForm.validateField("checkPass");
        return callback();
      } else {
        return callback(
          new Error("字母开头,长度6-18之间,允许字母数字和下划线")
        );
      }
    };
    let validateConfirmPass = (rule, value, callback) => {
      // 确认密码校验
      if (value === "") {
        return callback(new Error("请输入确认密码"));
      }
      // 校验是否以密码一致
      if (this.RegisterUser.pass != "" && value === this.RegisterUser.pass) {
        this.$refs.ruleForm.validateField("checkPass");
        return callback();
      } else {
        return callback(new Error("两次输入的密码不一致"));
      }
    };
    return {
      isRegister: false,  // 控制注册组件是否显示
      RegisterUser: {
        name: "",
        email: "",
        pass: "",
        confirmPass: ""
      },
      rules: {
        name: [{ validator: validateName, trigger: "blur" }],
        email: [{ validator: validateEmail, trigger: "blur" }],
        pass: [{ validator: validatePass, trigger: "blur" }],
        confirmPass: [{ validator: validateConfirmPass, trigger: "blur" }]
      }
    };
  },
  watch: {
    register(val) {
      if (val) {
        this.isRegister = val;
      }
    },
    isRegister(val) {
      if (!val) {
        this.$refs["ruleForm"].resetFields();
        this.$emit("fromChild", val);
      }
    }
  },
  methods: {
    Register() {
      this.$refs["ruleForm"].validate(valid => {
        if (valid) {
          this.$axios
            .post("/api/user/register", {
              userName: this.RegisterUser.name,
              password: this.RegisterUser.pass,
              email: this.RegisterUser.email
            })
            .then(res => {
              if (res.data.code === 200) {
                this.isRegister = false;
                this.notifySucceed(res.data.msg);
              } else {
                this.notifyError(res.data.msg);
              }
            })
            .catch(err => {
              return Promise.reject(err);
            });
        } else {
          return false;
        }
      });
    }
  }
};
</script>
<style scoped>
</style>
