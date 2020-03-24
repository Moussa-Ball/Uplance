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
        <button @click="gotoDashboard()"
          class="mark-as-read ripple-effect-dark"
          v-tippy="{
                        placement: 'left',
                        arrow: true,
                        maxWidth: 350,
                        theme: 'dark'
                    }"
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
                <a :href="notification.data.link">
                  <span class="notification-text" style="padding-left: 0; padding-right: 0;">
                    {{ notification.data.content }}
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
    gotoDashboard() {
      window.location.href = '/dashboard'
    },
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
          link: notification.link,
          content: notification.content,
        }
      });
    });
  }
};
</script>
