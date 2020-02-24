<template>
  <div>
    <!-- Dashboard Headline -->
    <div class="dashboard-headline">
      <h3>Messages</h3>
    </div>
    <div class="messages-container margin-top-0">
      <div class="messages-container-inner">
        <!-- Messages -->
        <div class="messages-inbox">
          <div class="messages-headline">
            <div class="input-with-icon">
              <input id="autocomplete-input" type="text" placeholder="Search" />
              <i class="icon-material-outline-search"></i>
            </div>
          </div>

          <ul>
            <li class="active-message">
              <a href="#">
                <div class="message-avatar">
                  <i class="status-icon status-offline"></i>
                  <img src="/images/user-avatar-small-02.jpg" alt />
                </div>

                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Sindy Forest</h5>
                    <span>Yesterday</span>
                  </div>
                  <p>Hi Tom! Hate to break it to you but I'm actually on vacation</p>
                </div>
              </a>
            </li>
          </ul>
        </div>
        <!-- Messages / End -->
      </div>
    </div>
    <!-- Messages Container / End -->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  computed: { ...mapGetters(["Messenger/conversations"]) },
  mounted() {
    this.$store.dispatch("Messenger/getPaticipants");
  }
};
</script>
