<template>
  <button
    @click="toggleBookmarkTarget($event)"
    class="bookmark-button margin-bottom-25"
    :class="{'bookmarked': bookmarked}"
  >
    <span class="bookmark-icon"></span>
    <span @click="toggleBookmark($event)" class="bookmark-text">Bookmark</span>
    <span @click="toggleBookmark($event)" class="bookmarked-text">Bookmarked</span>
  </button>
</template>

<script>
export default {
  props: ["hashid", "type"],
  data() {
    return {
      bookmarked: false
    };
  },
  methods: {
    async toggle() {
      this.axios
        .post("/api/bookmarks/toggle", {
          bookmark_id: this.hashid,
          bookmark_type: this.type
        })
        .then(response => {
          this.bookmarked = response.data;
        })
        .catch(error => {
          this.showErrors(error);
        });
    },
    toggleBookmarkTarget($event) {
      $($event.target).toggleClass("bookmarked");
      this.toggle();
    },
    toggleBookmark() {
      $(".bookmark-button").toggleClass("bookmarked");
    }
  },
  created() {
    this.axios
      .get(`/api/bookmarks/${this.hashid}/${this.type}`)
      .then(response => {
        this.bookmarked = response.data;
      })
      .catch(error => {
        this.showErrors(error);
      });
  }
};
</script>
