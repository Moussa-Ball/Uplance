import linkify from 'vue-linkify'
import clickOutside from "../directives/click-outside.js";

/**
 * You can register global directives & use them as a plugin in the main Vue instance
 */

const GlobalDirectives = {
    install(Vue) {
        Vue.directive('linkified', linkify);
        Vue.directive("click-outside", clickOutside);
    }
};

export default GlobalDirectives;