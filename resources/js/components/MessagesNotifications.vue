<template>
  <div class="header-notifications">
    <div class="header-notifications-trigger">
      <a @click.prevent.stop="redirectToMessenger" href="/messages">
        <i class="icon-feather-mail"></i>
        <span v-if="counter > 0">{{ counter }}</span>
      </a>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  props: ["user"],
  data() {
    return {
      counter: 0
    };
  },
  computed: {
    ...mapGetters({
      count: "Messenger/getCounter"
    })
  },
  watch: {
    count() {
      this.counter = this.count;
    }
  },
  methods: {
    redirectToMessenger() {
      window.location.href = "/messages";
    }
  },
  mounted() {
    let _this = this;
    Echo.private(`App.User.${_this.user}`).listen("NewMessage", e => {
      _this.counter += 1;
    });
  }
};
</script>
