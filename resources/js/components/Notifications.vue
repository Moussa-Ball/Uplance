<template>
  <div class="header-notifications">
    <!-- Trigger -->
    <div class="header-notifications-trigger">
      <a href="javascript:void;">
        <i class="icon-feather-bell"></i>
        <span v-if="count > 0">{{ count }}</span>
      </a>
    </div>

    <!-- Dropdown -->
    <div v-if="count > 0" class="header-notifications-dropdown">
      <div class="header-notifications-headline">
        <h4>Notifications</h4>
        <button
          @click="markAllAsRead"
          class="mark-as-read ripple-effect-dark"
          title="Mark all as read"
          data-tippy-placement="left"
        >
          <i class="icon-feather-check-square"></i>
        </button>
      </div>

      <div style="max-height: 390px;" class="header-notifications-content">
        <div class="header-notifications-scroll" data-simplebar>
          <ul>
            <template v-for="(notification, index) in notifications">
              <li :class="{'notifications-not-read': notification.read_at == null}" :key="index">
                <a
                  v-if="notification.type == 'App\\Notifications\\ProposalNotification'"
                  :href="notification.data.link"
                  @click.prevent.stop="markAsRead(notification.id, notification.data.link)"
                >
                  <span class="notification-text" style="padding-left: 0; padding-right: 0;">
                    <strong>{{ notification.data.name }}</strong> sent you a proposal on your
                    <span
                      class="color"
                    >{{ notification.data.project_name }}</span> project.
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
    markAsRead(id, url) {
      this.axios
        .put("/api/notifications/markAsRead/" + id)
        .then(response => {
          this.notifications = response.data;
          this.count--;
          window.location.href = url;
        })
        .catch(error => {
          this.showErrors(error);
        });
    },
    markAllAsRead() {
      this.axios
        .get("/api/notifications/markAllAsRead")
        .then(response => {
          this.notifications = response.data;
          response.data.filter(item => {
            if (item.read_at == null) this.count++;
          });
        })
        .catch(error => {
          this.showErrors(error);
        });
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
      console.log(notification);
      _this.getNotifications();
      if (document.hidden) {
        let audio = new Audio("/messages.mp3");
        audio.play();
        _this.favico.badge(_this.count);
      }
    });
  }
};
</script>
