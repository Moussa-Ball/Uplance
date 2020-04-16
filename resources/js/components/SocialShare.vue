<template>
  <!-- Share Buttons -->
  <div class="share-buttons margin-top-25">
    <div class="share-buttons-trigger">
      <i class="icon-feather-share-2"></i>
    </div>
    <div class="share-buttons-content">
      <span>
        Interesting?
        <strong>Share It!</strong>
      </span>
      <ul class="share-buttons-icons">
        <li @click.prevent="shareOnFacebook">
          <a
            style="background: #3b5998; cursor: pointer;"
            v-tippy="{ arrow : true }"
            content="Share on Facebook"
          >
            <i class="icon-brand-facebook-f"></i>
          </a>
        </li>
        <li @click.prevent="shareOnTwitter">
          <a
            style="background: #1da1f2; cursor: pointer;"
            v-tippy="{ arrow : true }"
            content="Share on Twitter"
          >
            <i class="icon-brand-twitter"></i>
          </a>
        </li>
        <li @click.prevent="shareOnLinkedin">
          <a
            style="background: #0077b5; cursor: pointer;"
            v-tippy="{ arrow : true }"
            content="Share on LinkedIn"
          >
            <i class="icon-brand-linkedin-in"></i>
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ["url"],
  methods: {
    popup(a, b, c, d) {
      c = c || 680;
      d = d || 360;
      window
        .open(
          a,
          b,
          "scrollbars=yes, width=" +
            c +
            ", height=" +
            d +
            ", top=" +
            ((window.screenTop || window.screenY) +
              (window.innerWidth || document.documentElement.clientHeight) / 2 -
              d) +
            ", " +
            "left=" +
            ((window.screenLeft || window.screenX) +
              (window.innerWidth || document.documentElement.clientWidth) / 2 -
              c / 2) +
            ""
        )
        .focus();
      return !0;
    },
    shareOnFacebook() {
      let url =
        "https://www.facebook.com/sharer/sharer.php?u=" +
        encodeURIComponent(this.url);
      this.popup(url, "Share on Facebook");
    },
    shareOnTwitter() {
      let url =
        "https://twitter.com/intent/tweet?text=" +
        encodeURIComponent(document.title) +
        "&via=UplanceHQ&url=" +
        encodeURIComponent(this.url);
      this.popup(url, "Share on Twitter");
    },
    shareOnLinkedin() {
      let url =
        "https://www.linkedin.com/shareArticle?url=" +
        encodeURIComponent(this.url);
      this.popup(url, "Share on Linkedin");
    }
  }
};
</script>
