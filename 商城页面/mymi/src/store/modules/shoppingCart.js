export default {
    state: {
        shoppingCart: []
        /*
        shoppingCart 结构
        shoppingCart = {
            id: "",
            productID: "",
            productName: "",
            productImg: "",
            price: "",
            num: "",
            maxNum: "",
            check: false    // 是否勾选
        }
        */
    },
    getters: {
        getShoppingCart(state) {
            // 获取购物车状态
            return state.shoppingCart;
        },
        getNum(state) {
            // 获取购物车商品总数量
            let totalNum = 0;
            for (let i = 0; i < state.shoppingCart.length; i++) {
                const temp = state.shoppingCart[i];
                totalNum += temp.num;
            }
            return totalNum;
        },
        getIsAllCheck(state) {
            // 判断是否全选
            let isAllCheck = true;
            for (let i = 0; i < state.shoppingCart.length; i++) {
                const temp = state.shoppingCart[i];
                if (!temp.check) {
                    isAllCheck = false;
                    return isAllCheck;
                }
            }
        },
        getCheckGoods(state) {
            // 获取勾选的商品信息，用于确认订单页面
            let checkGoods = [];
            for (let i = 0; i < state.shoppingCart.length; i++) {
                const temp = state.shoppingCart[i];
                if (temp.check) {
                    checkGoods.push(temp);
                }
            }
            return checkGoods;
        },
        getCheckNum(state) {
            // 获取购物车勾选的商品数量
            let totalNum = 0;
            for (let i = 0; i < state.shoppingCart.length; i++) {
                const temp = state.shoppingCart[i];
                if (temp.check) {
                    totalNum += temp.num;
                }
            }
            return totalNum;
        },
        getTotalPrice(state) {
            // 购物车勾选商品的总价格
            let totalPrice = 0;
            for (let i = 0; i < state.shoppingCart.length; i++) {
                const temp = state.shoppingCart[i];
                if (temp.check) {
                    totalPrice += temp.product_price * temp.num;
                }
            }
            return totalPrice;
        }
    },
    mutations: {
        setShoppingCart(state, data) {
            // 设置购物车状态
            state.shoppingCart = data;
        },
        unshiftShoppingCart(state, data) {
            // 添加商品进购物车
            // 用于在商品详情页点击添加购物车
            state.shoppingCart.unshift(data);
        },
        updateShoppingCart(state, payload) {
            // 更新购物车
            // 用于购物车点击勾选及加减商品数量
            if (payload.prop == "num") {
                if (payload.val < 1) {
                    // 判断当前商品数量是否小于 1 
                    return;
                }
            }
            // 根据商品在购物车的数组的索引和属性更改
            state.shoppingCart[payload.key][payload.prop] = payload.val;
        },
        addshoppingCartNum(state, productID) {
            // 增加购物车商品数量
            for (let i = 0; i < state.shoppingCart.length; i++) {
                const temp = state.shoppingCart[i];
                if (temp.productID == productID) {
                    if (temp.num < temp.maxNum) {
                        temp.num++;
                    }
                }
            }
        },
        deleteShoppingCart(state, id) {
            // 根据购物车 id 删除购物车商品
            for (let i = 0; i < state.shoppingCart.length; i++) {
                const temp = state.shoppingCart[i];
                console.log(temp);
                if (temp.product_id == id) {
                    state.shoppingCart.splice(i, 1);
                }
            }
        },
        checkAll(state, data) {
            // 点击全选按钮，更改每个商品的勾选状态
            for (let i = 0; i < state.shoppingCart.length; i++) {
                state.shoppingCart[i].check = data;
            }
        }
    },
    actions: {
        setShoppingCart({
            commit
        }, data) {
            commit('setShoppingCart', data);
        },
        unshiftShoppingCart({
            commit
        }, data) {
            commit('unshiftShoppingCart', data);
        },
        updateShoppingCart({
            commit
        }, payload) {
            commit('updateShoppingCart', payload);
        },
        addshoppingCartNum({
            commit
        }, productID) {
            commit('addShoppingCartNum', productID);
        },
        deleteShoppingCart({
            commit
        }, id) {
            commit('deleteShoppingCart', id);
        },
        checkAll({
            commit
        }, data) {
            commit('checkAll', data);
        }
    }
}