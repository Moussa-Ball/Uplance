import globalComponents from "./globalComponents";
import globalDirectives from "./globalDirectives";
import OnlinePresence from "./onlinePresence";
import globalMethods from "./globalMethods";
import VueLazyload from "vue-lazyload";

export default {
    install(Vue) {
        Vue.use(globalComponents);
        Vue.use(globalDirectives);
        Vue.use(OnlinePresence);
        Vue.use(globalMethods);
        Vue.use(VueLazyload);
    }
};