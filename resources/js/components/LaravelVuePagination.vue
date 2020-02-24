<template>
  <renderless-laravel-vue-pagination
    :data="data"
    :limit="limit"
    :show-disabled="showDisabled"
    :size="size"
    :align="align"
    v-on:pagination-change-page="onPaginationChangePage"
  >
    <ul
      v-if="computed.total > computed.perPage"
      slot-scope="{ data, limit, showDisabled, size, align, computed, prevButtonEvents, nextButtonEvents, pageButtonEvents }"
    >
      <li class="pagination-arrow" v-if="computed.prevPageUrl || showDisabled">
        <a
          @click="scrollTop"
          class="ripple-effect"
          href="#"
          aria-label="Previous"
          :tabindex="!computed.prevPageUrl && -1"
          v-on="prevButtonEvents"
        >
          <slot name="prev-nav">
            <i class="icon-material-outline-keyboard-arrow-left"></i>
          </slot>
        </a>
      </li>
      <li v-for="(page, key) in computed.pageRange" :key="key">
        <a
          @click="scrollTop"
          :class="{ 'current-page': page == computed.currentPage }"
          class="ripple-effect"
          href="#"
          v-on="pageButtonEvents(page)"
        >{{ page }}</a>
      </li>
      <li class="pagination-arrow" v-if="computed.nextPageUrl || showDisabled">
        <a
          @click="scrollTop"
          class="ripple-effect"
          href="#"
          aria-label="Next"
          :tabindex="!computed.nextPageUrl && -1"
          v-on="nextButtonEvents"
        >
          <slot name="next-nav">
            <i class="icon-material-outline-keyboard-arrow-right"></i>
          </slot>
        </a>
      </li>
    </ul>
  </renderless-laravel-vue-pagination>
</template>

<script>
import RenderlessLaravelVuePagination from "./RenderlessLaravelVuePagination.vue";
export default {
  props: {
    data: {
      type: Object,
      default: () => {}
    },
    limit: {
      default: 0,
      type: Number
    },
    showDisabled: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: "default",
      validator: value => {
        return ["small", "default", "large"].indexOf(value) !== -1;
      }
    },
    align: {
      type: String,
      default: "left",
      validator: value => {
        return ["left", "center", "right"].indexOf(value) !== -1;
      }
    }
  },

  methods: {
    onPaginationChangePage(page) {
      this.$emit("pagination-change-page", page);
    },
    scrollTop() {
      setTimeout(() => {
        $("html, body").animate({ scrollTop: 0 }, "slow");
      }, 500);
    }
  },

  components: {
    RenderlessLaravelVuePagination
  }
};
</script>