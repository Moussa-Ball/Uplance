import "./bootstrap";
import Vue from "vue";
import store from "./store";
import router from "./router";
import "./registerServiceWorker";
import Uplance from "./plugins/Uplance";

Vue.config.productionTip = true;
Vue.use(Uplance);

// eslint-disable-next-line no-unused-vars
const app = new Vue(Vue.util.extend({
    router, store, created() {
        this.$store.dispatch("Messenger/getConversations");
    }
})).$mount('#app');

