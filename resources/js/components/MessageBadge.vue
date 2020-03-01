<template>
  <span>
    <span v-if="count > 0" class="nav-tag">{{ count }}</span>
  </span>
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
