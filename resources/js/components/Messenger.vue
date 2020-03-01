<template>
  <div>
    <!-- Dashboard Headline -->
    <div class="dashboard-headline">
      <h3>Messages</h3>
    </div>

    <div
      v-if="loading == true && !conversations.threads"
      style="text-align: center;"
      class="padding-top-100 padding-bottom-100"
    >
      <strong style="font-size: 20px; font-weight: 300;">
        The messenger is in development. This is a beta version for simple discussion.
        We intend to facilitate discussions on different languages for some who do not speak the same language,
        particularly English. Please wait, we are loading your discussions.
      </strong>
      <div class="padding-top-60">
        <half-circle-spinner v-if="loading" :animation-duration="1000" :size="60" color="#2a41e8" />
      </div>
    </div>

    <div v-else-if="loading == false && !conversations.threads" style="text-align: center;">
      <img
        style="width: 400px; height: 600px; margin: 0 auto;"
        src="/images/list-messenger.svg"
        alt="messages.svg"
      />
      <strong
        style="font-size: 20px; font-weight: 300; margin-left: 60px;"
      >You have no messages yet.</strong>
    </div>

    <div
      v-else-if="loading == false && conversations.threads"
      class="messages-container margin-top-0"
    >
      <div class="messages-container-inner">
        <!-- Messages -->
        <div class="messages-inbox">
          <div class="messages-headline">
            <div class="input-with-icon">
              <input id="autocomplete-input" v-model="search" type="text" placeholder="Search" />
              <i class="icon-material-outline-search"></i>
            </div>
          </div>

          <ul>
            <template v-for="(conversation, key) in discussions.threads">
              <li
                :class="{'active-message': conversation.id == active.active_thread_id}"
                :key="key"
              >
                <router-link :to="{name: 'messages', params: {lang: 'en', id: conversation.id }}">
                  <div class="message-avatar">
                    <i
                      class="status-icon"
                      :class="{'status-online': conversation.user.presence_status === 'online' && conversation.user.switcher_status === 'online'}"
                    ></i>
                    <img :src="conversation.user.avatar" alt="avatar" />
                  </div>

                  <div class="message-by">
                    <div class="message-by-headline">
                      <h5>{{ conversation.user.name }}</h5>
                      <span v-if="conversation.latest_message">
                        <timeago
                          :datetime="conversation.latest_message.created_at"
                          :auto-update="true"
                        ></timeago>
                      </span>
                    </div>
                    <!-- :style="[conversation.unread ? {'font-weight':'800'} : '']" -->
                    <p
                      v-if="conversation.latest_message && JSON.parse(conversation.latest_message.body).type == 'text'"
                    >{{ JSON.parse(conversation.latest_message.body).content }}</p>
                  </div>
                </router-link>
              </li>
            </template>
          </ul>
        </div>
        <!-- Messages Content -->
        <router-view v-if="$route.name == 'messages'"></router-view>

        <template v-if="$route.name == 'messenger'">
          <div style="text-align: center; margin-left: 50px; margin-right: 50px;">
            <img
              style="width: 150px; height: 300px; margin: 50px auto;"
              src="/images/messages.svg"
              alt="messages.svg"
            />
            <strong
              style="font-size: 20px; font-weight: 300; margin-left: 60px;"
            >Select a user to view the discussion.</strong>
          </div>
        </template>
      </div>
    </div>
    <!-- Messages Container / End -->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      search: "",
      discussions: {},
      loading: false
    };
  },
  watch: {
    conversations() {
      this.discussions = {
        owner: this.conversations.owner,
        threads: this.conversations.threads
      };
    },
    search() {
      if (this.search) {
        this.$store.dispatch("Messenger/setSearching", true);
        if (this.discussions) {
          if (this.discussions.threads.length) {
            let filter = {};
            filter = this.discussions.threads.filter(discussion => {
              return discussion.user.name
                .toLowerCase()
                .includes(this.search.toLowerCase());
            });
            this.discussions.threads = filter;
          } else {
            this.discussions = {
              owner: this.conversations.owner,
              threads: this.conversations.threads
            };
            let filter = {};
            filter = this.discussions.threads.filter(discussion => {
              return discussion.user.name
                .toLowerCase()
                .includes(this.search.toLowerCase());
            });
            this.discussions.threads = filter;
          }
        }
      } else {
        this.$store.dispatch("Messenger/setSearching", false);
        this.discussions = {
          owner: this.conversations.owner,
          threads: this.conversations.threads
        };
      }
    }
  },
  computed: {
    ...mapGetters({
      conversations: "Messenger/getConversations",
      active: "Messenger/getActiveThread"
    })
  },
  async mounted() {
    let _this = this;

    Echo.join("uplance")
      .listen("UserOnline", function(user) {
        _this.$store.dispatch("Messenger/setUserPresence", user);
      })
      .listen("UserOffline", function(user) {
        _this.$store.dispatch("Messenger/setUserPresence", user);
      });
  }
};
</script>

<style lang="scss">
.message-bubble.me {
  .message-text {
    a {
      color: #fff;
      text-decoration: underline;
    }
  }
}
.message-bubble {
  .message-text {
    a {
      color: #2a41e8;
      text-decoration: underline;
    }
  }
}

.messages-inbox ul li:nth-child(1n) {
  background-color: #ffffff !important;
}
.messages-inbox ul li.active-message {
  background-color: #fafafa !important;
}
</style>
