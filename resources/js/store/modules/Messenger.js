/**
 * Manages user settings.
 */

const state = {
    conversations: {}
};

const getters = {};

const mutations = {};

const actions = {
    getPaticipants(context) {
        this._vm.axios.get('/api/messages/get_participants')
            .then(response => {
                console.log(response)
            })
            .catch(error => { this._vm.showErrors(error) })
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
