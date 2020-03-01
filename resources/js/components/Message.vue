<template>
  <div class="message-bubble" :class="{'me': message.user_id == messages.owner}">
    <div class="message-bubble-inner">
      <div class="message-avatar">
        <img :src="message.user.avatar" alt />
      </div>
      <div class="message-text">
        <p v-html="JSON.parse(message.body).content" v-linkified></p>
        <strong style="font-size: 13px; float: right;">
          <vue-moments-ago :date="message.created_at" lang="en" :prefix="''" suffix="ago"></vue-moments-ago>
        </strong>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</template>

<script>
export default {
  props: ["message", "messages"]
};
</script>
