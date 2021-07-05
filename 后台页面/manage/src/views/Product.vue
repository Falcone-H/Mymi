<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>
          <i class="el-icon-lx-cascades"></i> 商品管理
        </el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="container">
      <div class="handle-box">
        <el-button
          type="primary"
          icon="el-icon-delete"
          class="handle-del mr10"
          @click="delAllSelection"
        >批量删除</el-button>
      </div>
      <el-table
        :data="showList"
        border
        class="table"
        ref="multipleTable"
        header-cell-class-name="table-header"
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" width="55" align="center"></el-table-column>
        <el-table-column prop="product_id" label="ID" width="55" align="center"></el-table-column>
        <el-table-column prop="product_name" label="商品名"></el-table-column>
        <el-table-column label="价格">
          <template #default="scope">￥{{ scope.row.product_price }}</template>
        </el-table-column>
        <el-table-column label="商品图片" align="center">
          <template #default="scope">
            <el-image
              class="table-td-thumb"
              :src="$target + scope.row.product_picture"
              :preview-src-list="[$target + scope.row.product_picture]"
            ></el-image>
          </template>
        </el-table-column>

        <el-table-column label="操作" width="180" align="center">
          <template #default="scope">
            <el-button
              type="text"
              icon="el-icon-edit"
              @click="handleEdit(scope.$index, scope.row)"
            >编辑</el-button>
            <el-button
              type="text"
              icon="el-icon-delete"
              class="red"
              @click="handleDelete(scope.$index, scope.row)"
            >删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="pagination">
        <el-pagination
          background
          layout="total, prev, pager, next"
          :current-page="currentPage"
          :page-size="pageSize"
          :total="total"
          @current-change="handlePageChange"
        ></el-pagination>
      </div>
    </div>

    <!-- 编辑弹出框 -->
    <el-dialog title="编辑" v-model="editVisible" width="30%">
      <el-form ref="form" :model="form" label-width="70px">
        <el-form-item label="商品名">
          <el-input v-model="form.product_name"></el-input>
        </el-form-item>
        <el-form-item label="价格">
          <el-input v-model="form.product_price"></el-input>
        </el-form-item>
      </el-form>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="editVisible = false">取 消</el-button>
          <el-button type="primary" @click="saveEdit">确 定</el-button>
        </span>
      </template>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: "basetable",
  data() {
    return {
      showList: [],
      product_name: "",
      currentPage: 1,
      pageSize: 10,
      total: 0,
      multipleSelection: [],
      editVisible: false,
      form: {},
      idx: -1,
      id: -1,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    // 获取后台数据
    getData() {
      this.$axios
        .post("/api/admin/getProductData", {
          currentPage: this.currentPage,
          pageSize: this.pageSize,
        })
        .then((res) => {
          console.log(res.data.data.product);
          this.showList = res.data.data.product;
          this.total = res.data.data.total;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    // 删除操作
    handleDelete(index) {
      // 二次确认删除
      this.$confirm("确定要删除吗？", "提示", {
        type: "warning",
      })
        .then(() => {
          this.$message.success("删除成功");
          this.showList.splice(index, 1);
        })
        .catch(() => {});
    },
    // 多选操作
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },
    delAllSelection() {
      this.$confirm("确定要删除吗？", "提示", {
        type: "warning",
      })
        .then(() => {
          const length = this.multipleSelection.length;
          for (let i = 0; i < length; i++) {
            let id = this.multipleSelection[i].product_id;
            this.showList.forEach((item, index) => {
              if (item.product_id == id) {
                this.showList.splice(index, 1);
              }
            });
          }
          this.multipleSelection = [];
          this.$message.success("删除成功");
        })
        .catch(() => {});
    },
    // 编辑操作
    handleEdit(index, row) {
      this.idx = index;
      this.form = row;
      this.editVisible = true;
    },
    // 保存编辑
    saveEdit() {
      this.editVisible = false;
      this.$message.success(`修改第 ${this.idx + 1} 行成功`);
      this.$set(this.tableData, this.idx, this.form);
    },
    // 分页导航
    handlePageChange(val) {
      this.currentPage = val;
      this.getData();
    },
  },
};
</script>

<style scoped>
.handle-box {
  margin-bottom: 20px;
}

.handle-select {
  width: 120px;
}

.handle-input {
  width: 300px;
  display: inline-block;
}
.table {
  width: 100%;
  font-size: 14px;
}
.red {
  color: #ff0000;
}
.mr10 {
  margin-right: 10px;
}
.table-td-thumb {
  display: block;
  margin: auto;
  width: 40px;
  height: 40px;
}
</style>
