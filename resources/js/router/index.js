import Vue from "vue";
import VueRouter from "vue-router";


/**
 * Components Here.
 */
import SearchJob from '../components/SearchJob'
import PreviewJob from '../components/PreviewJob'


Vue.use(VueRouter);


// All routes.
const routes = [
    {
        name: "preview-job",
        path: "/:lang/jobs/~:id",
        component: PreviewJob,
    },
];

/**
 * Create and export routes.
 */
const router = new VueRouter({
    mode: "history",
    linkActiveClass: "active",
    linkExactActiveClass: "active",
    routes
});

export default router;
