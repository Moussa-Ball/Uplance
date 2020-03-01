import Vue from "vue";
import VueRouter from "vue-router";


/**
 * Components Here.
 */
import Messages from '../components/Messages'
import Messenger from '../components/Messenger'
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
    {
        path: '/:lang/messages',
        name: 'messenger',
        component: Messenger,
    },
    {
        path: '/:lang/messages/thread~:id',
        name: 'messages',
        component: Messages,
    }
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
