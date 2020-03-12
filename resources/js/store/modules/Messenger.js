/**
 * Manages messenger.
 */

import Favicon from "favico.js";

const audio = new Audio("/message.mp3");
const title = document.title;
const favicon = new Favicon({
    animation: "none",
    textColor: "#FFFFFF"
});

const favicoTitle = function(count) {
    if (document.hidden) {
        if (count > 0) {
            document.title = `(${count}) ${title}`;
            favicon.badge(count);
            favicon.badge(count);
            audio.play();
        }
    } else {
        document.title = title;
        favicon.reset();
    }
};

const state = {
    count: 0,
    messages: [],
    activeThread: 0,
    searchable: false,
    conversations: []
};

const getters = {
    getConversations(state) {
        return state.conversations;
    },
    getMessages(state) {
        return state.messages;
    },
    getActiveThread(state) {
        return state.activeThread;
    },
    getCounter(state) {
        return state.count;
    }
};

const mutations = {
    addConversations(state, conversations) {
        if (state.searchable == false) {
            state.conversations = conversations;
            let total = 0;
            for (let i in conversations.threads) {
                total += conversations.threads[i].unread;
                state.count = total;
            }
        }
    },
    addMessages(state, messages) {
        state.messages = messages;
    },
    pushMessage(state, message) {
        if (state.messages.messages) state.messages.messages.push(message);
        let messenger = $(".message-content-inner");
        var height = messenger[0].scrollHeight;
        messenger.animate({ scrollTop: height }, "slow");
    },
    addActiveThread(state, threadId) {
        state.activeThread = threadId;
    },
    setUserPresence(state, user) {
        for (let i in state.conversations.threads) {
            if (state.conversations.threads[i].user.hashid == user.hashid) {
                state.conversations.threads[i].user.presence_status =
                    user.presence_status;
                state.conversations.threads[i].user.switcher_status =
                    user.switcher_status;
            }
        }
    },
    setSearching(state, searchable) {
        state.searchable = searchable;
    },
    refreshCounter(state, counter) {
        state.count -= counter;
    },
    unShiftMessages(state, messages) {
        if (state.messages.messages) {
            if (messages[0]) {
                for (var i = messages.length; i--; ) {
                    state.messages.messages.unshift(messages[i]);
                }
            } else {
                state.messages.messages.unshift({ finish: true });
            }
        }
    }
};

const actions = {
    getConversations(context) {
        let conversations = null;
        this._vm.axios
            .get("/api/messages/conversations")
            .then(response => {
                conversations = response.data;
                context.commit("addConversations", conversations);
                Echo.private(`App.User.${response.data.owner}`).listen(
                    "NewMessage",
                    e => {
                        context.commit("pushMessage", e.message);
                        favicoTitle(context.state.count + 1);
                        this._vm.axios
                            .get("/api/messages/conversations")
                            .then(response => {
                                conversations = response.data;
                                context.commit(
                                    "addConversations",
                                    conversations
                                );
                            })
                            .catch(error => {
                                this._vm.showErrors(error);
                            });
                    }
                );
            })
            .catch(error => {
                this._vm.showErrors(error);
            });

        $(document).on("visibilitychange", function() {
            if (document.visibilityState === "visible") {
                favicoTitle();
            }
        });
    },
    async getMessages(context, threadId) {
        let response = null;
        await this._vm.axios
            .get(`/api/messages/discussions/${threadId}`)
            .then(response => {
                context.commit("addMessages", response.data);
            })
            .catch(error => {
                this._vm.showErrors(error);
            });
    },
    sendMessage(context, { threadId, content }) {
        this._vm.axios
            .post(`/api/messages/store/${threadId}`, { content: content })
            .then(response => {
                context.commit("pushMessage", response.data);
            })
            .catch(error => {
                this._vm.showErrors(error);
            });

        this._vm.axios
            .get("/api/messages/conversations")
            .then(response => {
                context.commit("addConversations", response.data);
            })
            .catch(error => {
                this._vm.showErrors(error);
            });
    },
    activeThread(context, threadId) {
        this._vm.axios
            .put("/api/messages/active/" + threadId, {})
            .then(response => {
                context.commit("addActiveThread", response.data);
            })
            .catch(error => {
                this._vm.showErrors(error);
            });
    },
    setUserPresence(context, user) {
        context.commit("setUserPresence", user);
    },
    setSearching(context, searchable) {
        context.commit("setSearching", searchable);
    },
    markAsRead(context, threadId) {
        this._vm.axios
            .put(`/api/messages/mark-as-read/${threadId}`, {})
            .then(response => {
                context.commit("refreshCounter", response.data);
            })
            .catch(error => {
                this._vm.showErrors(error);
            });
    },
    loadPreviousMessages(context, threadId) {
        let message = context.getters.getMessages.messages[0];
        if (message) {
            let url =
                "/api/messages/previous/" +
                threadId +
                "?before=" +
                message.created_at;
            this._vm.axios
                .get(url)
                .then(response => {
                    context.commit("unShiftMessages", response.data);
                })
                .catch(error => {
                    this._vm.showErrors(error);
                });
        }
    }
};

export default {
    strict: true,
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
