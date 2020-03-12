<template>
  <!-- Message Content -->
  <div class="message-content">
    <div class="messages-headline">
      <h4 v-if="messages.user">{{ messages.user.name }},</h4>
      <strong
        v-if=" messages.thread.subject.length > 25"
      >{{ messages.thread.subject.substr(0, 25) }}...</strong>
      <strong v-else>{{ messages.thread.subject }}</strong>
      <div class="message-action">
        <a v-if="false" href="javascript:void;">
          <i class="icon-feather-video"></i> Video Call
        </a>
        <a v-if="false" href="javascript:void;">
          <i class="icon-feather-phone-call"></i> Voice Call
        </a>
        <a href="javascript:void;">
          <i class="icon-feather-trash-2"></i> Delete Conversation
        </a>
      </div>
    </div>

    <!-- Message Content Inner -->
    <div
      class="message-content-inner"
      v-chat-scroll="{always: false, smooth: true, scrollonremoved:true, smoothonremoved: false}"
      style="height: 480px;"
    >
      <!-- Message Content -->
      <template v-for="(message, key) in messages.messages">
        <Message
          v-if="!message.hasOwnProperty('finish')"
          :message="message"
          :messages="messages"
          :key="key"
        ></Message>
      </template>
      <!-- Message Content End -->
      <div
        v-if="messages.thread && messages.thread.id === typing.threadId && !messages.messages[0].hasOwnProperty('finish')"
        class="message-bubble"
      >
        <div class="message-bubble-inner">
          <div class="message-avatar">
            <img :src="messages.user.avatar" alt="avatar" />
          </div>
          <div class="message-text">
            <div class="typing-indicator">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    <!-- Message Content Inner / End -->

    <!-- Reply Area -->
    <div class="message-reply">
      <textarea
        v-if="!loading"
        cols="1"
        rows="1"
        ref="messengerTextarea"
        placeholder="Your Message"
        @keypress.enter="sendMessage"
        data-autoresize
        v-model="content"
        style="overflow: hidden;"
      ></textarea>
      <picker
        @select="onEmojiSelect"
        title="uplance"
        set="emojione"
        :native="true"
        v-show="showEmoji && !loading"
        class="picker"
      />

      <div v-show="!loading" class="ml-1">
        <div class="emoji-invoker">
          <i
            class="icon-material-outline-attach-file"
            style="font-size: 20px; position: relative; bottom: 1px; cursor: pointer;"
            @click="attachFile"
          ></i>
          <input
            class="file-upload"
            style="display:none;"
            type="file"
            accept="image/*, application/pdf"
          />
        </div>
      </div>
      <div v-show="!loading" class="ml-1">
        <div @mousedown.prevent="toggleEmojiPicker" class="emoji-invoker">
          <i class="icon-material-outline-face" style="font-size: 20px; cursor: pointer;"></i>
        </div>
      </div>

      <button
        v-if="!loading"
        @click="sendMessage"
        style="width: 100px;"
        class="button ripple-effect"
      >Send</button>
      <half-circle-spinner v-if="loading" :animation-duration="1000" :size="60" color="#2a41e8" />
    </div>
  </div>
  <!-- Message Content -->
</template>

<script>
import Message from "./Message";
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      typing: 0,
      content: "",
      loading: false,
      showEmoji: false,
      typingTime: false,

      timeout: null,
      scrolled: false,
      scrolledTop: false
    };
  },
  computed: { ...mapGetters({ messages: "Messenger/getMessages" }) },
  components: { Message },
  methods: {
    attachFile() {
      $(".file-upload").click();
    },
    async sendMessage(e) {
      let _this = this;
      if (e.shiftKey === false) {
        e.preventDefault();
        if (!_this.content.length)
          return this.showNotification(
            "You must write a message.",
            "error",
            true,
            3000
          );
        try {
          await this.$store.dispatch("Messenger/sendMessage", {
            threadId: _this.$route.params.id,
            content: JSON.stringify({ type: "text", content: _this.content })
          });
          _this.content = "";
          _this.showEmoji = false;
        } catch (e) {}
      }
    },
    toggleEmojiPicker() {
      this.showEmoji = !this.showEmoji;
    },
    onEmojiSelect(emoji) {
      this.content += emoji.native;
      const textarea = this.$refs.messengerTextarea;
      textarea.focus();
    },
    async onScroll() {
      if (this.$messages.scrollTop === 0) {
        this.loading = true;
        let messenger = $(".message-content-inner");
        let previousHeight = messenger[0].scrollHeight;
        await this.$store.dispatch(
          "Messenger/loadPreviousMessages",
          this.$route.params.id
        );
        setTimeout(() => {
          let messenger = $(".message-content-inner");
          var height = messenger[0].scrollHeight - previousHeight;
          messenger.animate({ scrollTop: height }, "slow");
          this.loading = false;
          if (this.messages.messages[0].hasOwnProperty("finish")) {
            this.$messages.removeEventListener("scroll", this.onScroll);
          }
        }, 1000);
      }
    }
  },
  watch: {
    content() {
      if (this.content.length) {
        let threadId = this.messages.thread.id;
        let avatar = this.messages.user.avatar;

        if (!this.typing.active) {
          Echo.private("messenger").whisper("typing", {
            threadId: threadId
          });
          clearTimeout(this.typingTime);
          this.typingTime = setTimeout(() => {
            Echo.private("messenger").whisper("typing", {
              threadId: 0
            });
          }, 5000);
        }
      } else {
        Echo.private("messenger").whisper("typing", {
          threadId: 0
        });
      }
    },
    "$route.params.id": async function() {
      this.loading = true;
      await this.$store.dispatch(
        "Messenger/getMessages",
        this.$route.params.id
      );
      await this.$store.dispatch(
        "Messenger/activeThread",
        this.$route.params.id
      );
      this.loading = false;
    },
    "messages.messages": function() {
      this.$store.dispatch("Messenger/markAsRead", this.$route.params.id);
    }
  },
  async mounted() {
    let _this = this;
    _this.loading = true;
    await this.$store.dispatch("Messenger/getMessages", this.$route.params.id);
    await this.$store.dispatch("Messenger/activeThread", this.$route.params.id);

    // Auto Resizing Message Input Field
    await setTimeout(() => {
      jQuery.each(jQuery("textarea[data-autoresize]"), function() {
        var offset = this.offsetHeight - this.clientHeight;

        var resizeTextarea = function(el) {
          jQuery(el)
            .css("height", "auto")
            .css("height", el.scrollHeight + offset);
        };
        jQuery(this)
          .on("keyup input", function() {
            resizeTextarea(this);
          })
          .removeAttr("data-autoresize");
      });

      _this.$messages = _this.$el.querySelector(".message-content-inner");
      _this.$messages.addEventListener("scroll", _this.onScroll);
    }, 100);

    Echo.private("messenger").listenForWhisper("typing", e => {
      _this.typing = e;
    });

    _this.loading = false;
  }
};
</script>

<style lang="scss">
.ml-1 {
  flex: auto;
  flex-grow: 0;
  align-self: center;
}

.message-reply textarea {
  min-width: 60%;
}

.emoji-invoker {
  padding: 10px 20px 0 0;
}

.emoji-invoker > svg {
  fill: #b1c6d0;
}

.picker {
  flex: auto;
  flex-grow: 0;
  align-self: center;
  position: absolute;
  top: 280px;
  right: 55px;
}

@media screen and (max-width: 992px) {
  .picker {
    position: absolute;
    top: 245px;
    right: 20px;
  }
}

@media screen and (max-width: 768px) {
  .picker {
    position: absolute;
    top: 480px;
    right: 20px;
  }
}

@media screen and (max-width: 576px) {
  .picker {
    position: absolute;
    top: 480px;
    right: 10px;
  }
}

.message-action {
  a {
    color: #333;
    padding-left: 15px;
    &:hover {
      color: #2a41e8;
      transition: color 0.5s;
    }
  }
}
</style>
