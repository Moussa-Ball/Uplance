/**
 * ----------------------------------------------------------
 * ----------------- Users Presence System ------------------
 * ----------------------------------------------------------
 */


const OnlinePresence = {
    // eslint-disable-next-line no-unused-vars
    install(Vue) {
        Echo.join('uplance')
            .joining((user) => {
                axios.put(`/api/user/${user}/online`, {});
            })
            .leaving((user) => {
                axios.put(`/api/user/${user}/offline`, {});
            })
    }
};

export default OnlinePresence;