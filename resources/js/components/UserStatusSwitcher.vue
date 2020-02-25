<template>
  <div class="status-switch">
    <label
      @click="online(user)"
      :class="{'current-status': status === 'online'}"
      class="user-online"
    >Online</label>
    <label
      @click="offline(user)"
      :class="{'current-status': status === 'offline'}"
      class="user-invisible"
    >Invisible</label>
    <!-- Status Indicator -->
    <span class="status-indicator" aria-hidden="true"></span>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      status: this.user.switcher_status
    };
  },
  watch: {
    status() {
      if (this.status === "online") {
        axios.put(`/api/user/switcher/${this.user.id}/online`, {});
        $(".user-avatar").addClass("status-online");
        $(".user-online").addClass("current-status");
        $(".user-avatar").removeClass("status-inactif");
        $(".user-invisible").removeClass("current-status");
      } else {
        axios.put(`/api/user/switcher/${this.user.id}/offline`, {});
        $(".user-avatar").removeClass("status-online");
        $(".user-avatar").removeClass("status-inactif");
        $(".user-online").removeClass("current-status");
        $(".user-invisible").addClass("current-status");
      }
    }
  },
  methods: {
    online() {
      this.status = "online";
    },
    offline() {
      this.status = "offline";
    }
  },
  async mounted() {
    if (this.user.switcher_status === "online") {
      $(".user-avatar").addClass("status-online");
      $(".user-online").addClass("current-status");
      $(".user-invisible").removeClass("current-status");
    } else {
      $(".user-avatar").removeClass("status-online");
      $(".user-online").removeClass("current-status");
      $(".user-invisible").addClass("current-status");
    }
  }
};
</script>
