<template>
  <div class="header-notifications">
    <!-- Trigger -->
    <div class="header-notifications-trigger">
      <a @click.prevent="markAllAsRead()" href="javascript:void;">
        <i class="icon-feather-bell"></i>
        <span v-if="count > 0">{{ count }}</span>
      </a>
    </div>

    <!-- Dropdown -->
    <div v-if="notifications.length" class="header-notifications-dropdown">
      <div class="header-notifications-headline">
        <h4>Notifications</h4>
        <button
          class="mark-as-read ripple-effect-dark"
          v-tippy="{placement: 'left',  arrow: true, maxWidth: 350, theme: 'dark'}"
          content="See All Notifications"
        >
          <i class="icon-feather-list"></i>
        </button>
      </div>

      <div style="max-height: 390px;" class="header-notifications-content">
        <div class="header-notifications-scroll" data-simplebar>
          <ul>
            <template v-for="(notification, index) in notifications">
              <li :key="index">
                <a
                  v-if="notification.type == 'App\\Notifications\\ProposalSend'"
                  :href="notification.data.link"
                >
                  <span class="notification-text" style="padding-left: 0; padding-right: 0;">
                    <strong>{{ notification.data.name }}</strong> sent you a proposal on your
                    <span
                      class="color"
                    >{{ notification.data.project_name }}</span> project.
                  </span>
                </a>
                <a
                  v-if="notification.type == 'App\\Notifications\\ProposalRejected'"
                  :href="notification.data.link"
                >
                  <span class="notification-text" style="padding-left: 0; padding-right: 0;">
                    Your proposal for
                    <span class="color">{{ notification.data.project_name }}</span>
                    project has benen rejected by the client.
                  </span>
                </a>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      count: 0,
      notifications: []
    };
  },
  methods: {
    markAllAsRead() {
      if (this.count >= 1) {
        this.axios
          .get("/api/notifications/markAllAsRead")
          .then(response => {
            this.notifications = response.data;
            this.count = 0;
          })
          .catch(error => {
            this.showErrors(error);
          });
      }
    },
    async getNotifications() {
      await this.axios
        .get("/api/notifications")
        .then(response => {
          this.notifications = response.data;
          response.data.filter(item => {
            if (item.read_at == null) this.count++;
          });
        })
        .catch(error => {
          this.showErrors(error);
        });
    }
  },
  async mounted() {
    let _this = this;
    await _this.getNotifications();
    Echo.private(`App.User.${_this.user}`).notification(notification => {
      this.count++;
      _this.notifications.unshift({
        type: notification.type,
        data: {
          name: notification.name,
          project_name: notification.project_name,
          link: notification.link
        }
      });
      if (document.hidden) {
        let audio = new Audio("/messages.mp3");
        audio.play();
        _this.favico.badge(_this.count);
      }
    });
  }
};
</script>
