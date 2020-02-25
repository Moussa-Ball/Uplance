<template>
  <div>
    <div class="dashboard-headline">
      <h3>Hire Freelancer</h3>
    </div>

    <div class="margin-top-70"></div>

    <div class="row">
      <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
          <!-- Headline -->
          <div class="headline">
            <h3>
              <span style="display: flex; justify-content: space-between;">
                <span style="position: relative; top: 12px;">
                  <i class="icon-material-outline-business-center"></i>
                  Hire
                </span>
                <span
                  @click="previewFreelancer"
                  class="button apply-now-button popup-with-zoom-anim"
                >See the profile</span>
              </span>
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    previewFreelancer() {}
  }
};
</script>
