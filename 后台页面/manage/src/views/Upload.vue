<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>
          <i class="el-icon-lx-calendar"></i> 添加商品
        </el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="container">
      <div class="form-box">
        <el-form ref="form" :model="form" label-width="80px">
          <el-form-item label="商品名称">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="种类">
            <el-select v-model="form.region" placeholder="请选择">
              <el-option key="phone" label="手机" value="phone"></el-option>
              <el-option key="television" label="电视" value="television"></el-option>
              <el-option key="washingMachine" label="洗衣机" value="washingMachine"></el-option>
              <el-option key="protectSleeve" label="保护套" value="protectSleeve"></el-option>
              <el-option key="charger" label="充电宝" value="charger"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="上架时间">
            <el-col :span="11">
              <el-date-picker
                type="date"
                placeholder="选择日期"
                v-model="form.date1"
                style="width: 100%;"
              ></el-date-picker>
            </el-col>
            <el-col class="line" :span="2">&nbsp;-&nbsp;</el-col>
            <el-col :span="11">
              <el-time-picker placeholder="选择时间" v-model="form.date2" style="width: 100%;"></el-time-picker>
            </el-col>
          </el-form-item>
          <el-form-item label="单选框">
            <el-radio-group v-model="form.resource">
              <el-radio label="包邮"></el-radio>
              <el-radio label="不包邮"></el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="描述">
            <el-input type="textarea" rows="5" v-model="form.desc"></el-input>
          </el-form-item>
          <el-form-item label="商品图片">
            <el-upload
              list-type="picture-card"
              action="#"
              :auto-upload="false"
              :on-preview="handlePictureCardPreview"
              :on-remove="handleRemove"
              :before-upload="beforeUpload"
            >
              <i class="el-icon-plus"></i>
            </el-upload>
            <el-dialog v-model="dialogVisible">
              <img width="100%" :src="dialogImageUrl" alt />
            </el-dialog>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">提交</el-button>
            <el-button>取消</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import "cropperjs/dist/cropper.css";
export default {
  name: "upload",
  data() {
    return {
      dialogImageUrl: "",
      dialogVisible: false,
      fileList: [],
      imgSrc: "",
      cropImg: "",
      form: {
        name: "",
        region: "",
        date1: "",
        date2: "",
        resource: "包邮",
        desc: "",
        options: [],
      },
    };
  },
  methods: {
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePictureCardPreview(file) {
      console.log(file.url);
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    beforeUpload(file) {
      console.log(file);
    },
    onSubmit() {
      this.$message({
        message: "提交成功",
        type: "success",
      });
    },
  },
};
</script>

<style scoped>
.content-title {
  font-weight: 400;
  line-height: 50px;
  margin: 10px 0;
  font-size: 22px;
  color: #1f2f3d;
}

.pre-img {
  width: 100px;
  height: 100px;
  background: #f8f8f8;
  border: 1px solid #eee;
  border-radius: 5px;
}
.crop-demo {
  display: flex;
  align-items: flex-end;
}
.crop-demo-btn {
  position: relative;
  width: 100px;
  height: 40px;
  line-height: 40px;
  padding: 0 20px;
  margin-left: 30px;
  background-color: #409eff;
  color: #fff;
  font-size: 14px;
  border-radius: 4px;
  box-sizing: border-box;
}
.crop-input {
  position: absolute;
  width: 100px;
  height: 40px;
  left: 0;
  top: 0;
  opacity: 0;
  cursor: pointer;
}
</style>