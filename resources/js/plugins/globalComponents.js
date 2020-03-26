window.axios = require("axios");
import Places from "vue-places";
import VueAxios from "vue-axios";
import VueTelInput from "vue-tel-input";
import Multiselect from "vue-multiselect";
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/antd.css";
import { SlideXRightTransition } from "vue2-transitions";
import {
    ContentLoader,
    FacebookLoader,
    CodeLoader,
    BulletListLoader,
    InstagramLoader,
    ListLoader
} from "vue-content-loader";
import vueCountryRegionSelect from "vue-country-region-select";
import { HalfCircleSpinner, SelfBuildingSquareSpinner } from "epic-spinners";
import money from "v-money";
import MoneyFormat from "vue-money-format";
import LaravelVuePagination from "../components/LaravelVuePagination.vue";
import ReadMore from "vue-read-more";
import VueMomentsAgo from "vue-moments-ago";
import StarRating from "vue-star-rating";
import "@voerro/vue-tagsinput/dist/style.css";
import VoerroTagsInput from "@voerro/vue-tagsinput";
import VueTippy, { TippyComponent } from "vue-tippy";
import "tippy.js/themes/light.css";
import Nl2br from "vue-nl2br";
import { Datetime } from "vue-datetime";
import "vue-datetime/dist/vue-datetime.css";
import VueTimeago from "vue-timeago";
import VueChatScroll from "vue-chat-scroll";
import { Picker } from "emoji-mart-vue";

/**
 * Import Component to display.
 */
import App from "../components/App";
import PostJob from "../components/PostJob";
import EditJob from "../components/EditJob";
import SearchJob from "../components/SearchJob";
import PreviewJob from "../components/PreviewJob";
import Bookmark from "../components/Bookmark";
import SocialShare from "../components/SocialShare";
import ProfileSettings from "../components/ProfileSettings";
import SubmitJob from "../components/SubmitJob";
import Notifications from "../components/Notifications";
import Messenger from "../components/Messenger";
import HireFreelancer from "../components/HireFreelancer";
import SearchFreelancer from "../components/SearchFreelancer";
import UserStatusSwitcher from "../components/UserStatusSwitcher";
import MessagesNotifications from "../components/MessagesNotifications";
import MessageBadge from "../components/MessageBadge";
import Contract from "../components/Contract";
import StoreReview from "../components/StoreReview";
import Premium from "../components/Premium";

export default {
    install(Vue) {
        /**
         * ------------------------------------
         * Global Using.
         * ------------------------------------
         */

        Vue.use(ReadMore);
        Vue.use(VueChatScroll);
        Vue.use(VueTelInput);
        Vue.use(VueAxios, axios);
        Vue.use(money, { precision: 4 });
        Vue.use(vueCountryRegionSelect);
        Vue.use(StarRating);
        Vue.use(Bookmark);
        Vue.use(Picker);
        Vue.use(SocialShare);
        Vue.use(VueTippy, {
            directive: "tippy", // => v-tippy
            flipDuration: 0,
            popperOptions: {
                modifiers: {
                    preventOverflow: {
                        enabled: false
                    }
                }
            }
        });

        Vue.use(Datetime);

        Vue.use(VueTimeago, {
            name: "Timeago", // Component name, `Timeago` by default
            locale: "en", // Default locale
            // We use `date-fns` under the hood
            // So you can use all locales from it
            locales: {
                "zh-CN": require("date-fns/locale/zh_cn"),
                ja: require("date-fns/locale/ja")
            }
        });

        /**
         * ------------------------------------
         * Global Components.
         * ------------------------------------
         */
        Vue.component("app", App);
        Vue.component("places", Places);
        Vue.component("VueSlider", VueSlider);
        Vue.component("multiselect", Multiselect);
        Vue.component("ContentLoader", ContentLoader);
        Vue.component("FacebookLoader", FacebookLoader);
        Vue.component("BulletListLoader", BulletListLoader);
        Vue.component("InstagramLoader", InstagramLoader);
        Vue.component("ListLoader", ListLoader);
        Vue.component("search-job", SearchJob);
        Vue.component("CodeLoader", CodeLoader);
        Vue.component("post-job", PostJob);
        Vue.component("edit-job", EditJob);
        Vue.component("profile-settings", ProfileSettings);
        Vue.component("half-circle-spinner", HalfCircleSpinner);
        Vue.component(
            "self-building-square-spinner",
            SelfBuildingSquareSpinner
        );
        Vue.component("money-format", MoneyFormat);
        Vue.component("pagination", LaravelVuePagination);
        Vue.component("slide-x-right-transition", SlideXRightTransition);
        Vue.component("vue-moments-ago", VueMomentsAgo);
        Vue.component("preview-job", PreviewJob);
        Vue.component("star-rating", StarRating);
        Vue.component("bookmark", Bookmark);
        Vue.component("social-share", SocialShare);
        Vue.component("tags-input", VoerroTagsInput);
        Vue.component("tippy", TippyComponent);
        Vue.component("submit-job", SubmitJob);
        Vue.component("nl2br", Nl2br);
        Vue.component("datetime", Datetime);
        Vue.component("notifications", Notifications);
        Vue.component("messenger", Messenger);
        Vue.component("hire-freelancer", HireFreelancer);
        Vue.component("search-freelancer", SearchFreelancer);
        Vue.component("user-status-switcher", UserStatusSwitcher);
        Vue.component("messages-notifications", MessagesNotifications);
        Vue.component("message-badge", MessageBadge);
        Vue.component("picker", Picker);
        Vue.component("contract", Contract);
        Vue.component("store-review", StoreReview);
        Vue.component("premium", Premium);
    }
};
