<template>
  <div class="container">
    <div class="row">
      <div class="col-xl-12">
        <!-- Billing Cycle  -->
        <div class="margin-bottom-70"></div>

        <!-- Pricing Plans Container -->
        <div class="pricing-plans-container">
          <!-- Plan -->
          <div class="pricing-plan">
            <h3>Basic Free</h3>
            <p class="margin-top-10">The minimum to start on uplance and start winning.</p>
            <div class="pricing-plan-label billed-monthly-label">
              <strong>$0</strong>
            </div>
            <div class="pricing-plan-features">
              <strong>Features of Basic Free</strong>
              <ul>
                <li>30 credits / monthly</li>
                <li>2 credits = 1 Bid</li>
                <li>Unlimited Portfolio (Soon)</li>
                <li>Unlimited Bookmark</li>
                <li>Automatic offers</li>
              </ul>
            </div>
          </div>

          <!-- Plan -->
          <div class="pricing-plan recommended">
            <div class="recommended-badge">Recommended</div>
            <h3>Pro</h3>
            <p class="margin-top-10">For freelancers or clients seeking to stand out from others.</p>
            <div class="pricing-plan-label billed-monthly-label">
              <strong>$29</strong>/ monthly
            </div>
            <div class="pricing-plan-features">
              <strong>Features of Pro Plan</strong>
              <ul>
                <li>100 credits / monthly</li>
                <li>2 credits = 1 Bid</li>
                <li>Unlimited Portfolio (Soon)</li>
                <li>Unlimited Bookmark</li>
                <li>Profile Badge</li>
                <li>Automatic offers</li>
              </ul>
            </div>
            <a
              @click.prevent.stop="subscribePro"
              v-if="!loading && pro_activated == 0"
              href="#"
              class="button full-width margin-top-20"
            >Select</a>
            <a
              @click.prevent.stop="cancelProSubscription"
              v-else-if="!loading && pro_activated == 1"
              href="#"
              class="button full-width margin-top-20"
            >Cancel</a>
            <span style="position: relative; top: 15px;">
              <half-circle-spinner
                v-if="loadingPro"
                :animation-duration="1000"
                :size="60"
                color="#2a41e8"
              />
            </span>
            {{ pro_activated }} {{ business_activated }}
          </div>

          <!-- Plan -->
          <div class="pricing-plan">
            <h3>Business</h3>
            <p class="margin-top-10">For freelancers or clients who want to do their business.</p>
            <div class="pricing-plan-label billed-monthly-label">
              <strong>$99</strong>/ monthly
            </div>
            <div class="pricing-plan-features">
              <strong>Features of Business Plan</strong>
              <ul>
                <li>Unlimited credits</li>
                <li>Unlimited proposals</li>
                <li>Unlimited Portfolio (Soon)</li>
                <li>Unlimited Bookmark</li>
                <li>Profile Badge</li>
                <li>Automatic offers</li>
              </ul>
            </div>
            <a
              @click.prevent.stop="subscribeBusiness"
              v-if="!loading && !business_activated"
              href="#"
              class="button full-width margin-top-20"
            >Select</a>
            <a
              @click.prevent.stop="cancelBusinessSubscription"
              v-else-if="!loading && business_activated"
              href="#"
              class="button full-width margin-top-20"
            >Cancel</a>
            <span style="position: relative; top: 15px;">
              <half-circle-spinner
                v-if="loadingBusiness"
                :animation-duration="1000"
                :size="60"
                color="#2a41e8"
              />
            </span>
            {{ pro_activated }} {{ business_activated }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    userId: String,
    subscribed_to_pro: Boolean,
    grace_period_in_pro: Boolean,
    subscribed_to_business: Boolean,
    grace_period_in_business: Boolean
  },
  data() {
    return {
      loading: false,
      loadingPro: false,
      loadingBusiness: false,
      pro_grace: this.grace_period_in_pro,
      pro_activated: this.subscribed_to_pro,
      business_grace: this.grace_period_in_business,
      business_activated: this.subscribed_to_business
    };
  },
  methods: {
    subscribePro() {
      this.loading = true;
      this.loadingPro = true;

      this.axios
        .post(`/api/membership/subscribe/pro/~${this.user_id}`, {})
        .then(response => {
          this.loadingPro = false;
          this.showNotification(response.data.message, "success", true, 5000);
        })
        .catch(error => {
          this.showErrors(error);
          this.loading = false;
          this.loadingPro = false;
        });
    },
    subscribeBusiness() {
      this.loading = true;
      this.loadingBusiness = true;

      this.axios
        .post(`/api/membership/subscribe/business/~${this.user_id}`, {})
        .then(response => {
          this.loadingBusiness = false;
          this.pro_activated = response.data.subscribed_to_pro;
          this.business_activated = response.data.subscribed_to_business;
          this.showNotification(response.data.message, "success", true, 5000);
        })
        .catch(error => {
          this.showErrors(error);
          this.loading = false;
          this.loadingBusiness = false;
        });
    },
    cancelProSubscription() {
      this.loading = true;
      this.loadingPro = true;

      this.axios
        .post(`/api/membership/subscribe/pro/cancel/~${this.user_id}`, {})
        .then(response => {
          this.loadingPro = false;
          this.pro_activated = response.data.subscribed_to_pro;
          this.business_activated = response.data.subscribed_to_business;
          this.showNotification(response.data.message, "success", true, 5000);
        })
        .catch(error => {
          this.showErrors(error);
          this.loading = false;
          this.loadingPro = false;
        });
    },
    subscribeBusiness() {
      this.loading = true;
      this.loadingBusiness = true;

      this.axios
        .post(`/api/membership/subscribe/business/~${this.user_id}`, {})
        .then(response => {
          this.loadingBusiness = false;
          this.pro_activated = response.data.subscribed_to_pro;
          this.business_activated = response.data.subscribed_to_business;
          this.showNotification(response.data.message, "success", true, 5000);
        })
        .catch(error => {
          this.showErrors(error);
          this.loading = false;
          this.loadingBusiness = false;
        });
    },
    cancelBusinessSubscription() {
      this.loading = true;
      this.loadingBusiness = true;

      this.axios
        .post(`/api/membership/subscribe/business/cancel/~${this.user_id}`, {})
        .then(response => {
          this.loadingBusiness = false;
          this.pro_activated = response.data.subscribed_to_pro;
          this.business_activated = response.data.subscribed_to_business;
          this.showNotification(response.data.message, "success", true, 5000);
        })
        .catch(error => {
          this.showErrors(error);
          this.loading = false;
          this.loadingBusiness = false;
        });
    }
  }
};
</script>
