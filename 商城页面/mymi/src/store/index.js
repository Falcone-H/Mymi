import {
    createStore
} from 'vuex'
import shoppingCart from "./modules/shoppingCart"
import user from "./modules/user"


const store = createStore({
    modules: {
        shoppingCart,
        user
    }
})

export default store;