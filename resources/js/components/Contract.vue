<template>
  <div>
    <div class="dashboard-headline">
      <h3>Contract</h3>
      <span>{{ contrat.title }}</span>
    </div>

    <div class="row">
      <div class="col-xl-12">
        <div class="contrat-container contrat-card">
          <!-- Employer Details -->
          <header v-if="user != contrat.from.hashid" class="contrat-header">
            <div class="row-card">
              <div class="col-md-7">
                <h2>{{ contrat.title }}</h2>
                <span v-if="contrat.completed" class="progress-job-container">
                  <progress class="progress-job" max="100" :value="100"></progress> 100%
                </span>
              </div>
              <div class="col-md-5">
                <div class="row-card">
                  <img :src="contrat.from.avatar" class="avatar" alt="avatar" />
                  <div
                    class="user-presence-indicator"
                    :class="{'status-online': contrat.from.presence_status == 'online' && contrat.from.switcher_status == 'online'}"
                  ></div>
                  <div class="client-information">
                    <h4
                      class="client-name"
                    >{{ contrat.from.first_name }} {{ contrat.from.last_name }}</h4>
                    <span>{{ contrat.from.tagline }}</span>
                    <star-rating
                      :style="{position: 'relative', top: 1 + 'px'}"
                      :star-size="20"
                      :read-only="true"
                      :show-rating="false"
                      :rating="contrat.from.rating"
                    ></star-rating>
                  </div>
                </div>
              </div>
            </div>
          </header>
          <!-- Freelancer Details -->
          <header v-if="user != contrat.to.hashid" class="contrat-header">
            <div class="row-card">
              <div class="col-md-7">
                <h2>{{ contrat.title }}</h2>
                <span v-if="contrat.completed" class="progress-job-container">
                  <progress class="progress-job" max="100" :value="100"></progress> 100%
                </span>
              </div>
              <div class="col-md-5">
                <div class="row-card">
                  <img :src="contrat.to.avatar" class="avatar" alt="avatar" />
                  <div
                    class="user-presence-indicator"
                    :class="{'status-online': contrat.to.presence_status === 'online' && contrat.to.switcher_status === 'online'}"
                  ></div>
                  <div class="client-information">
                    <h4 class="client-name">{{ contrat.to.first_name }} {{ contrat.to.last_name }}</h4>
                    <span>{{ contrat.to.tagline }}</span>
                    <star-rating
                      :style="{position: 'relative', top: 1 + 'px'}"
                      :star-size="20"
                      :read-only="true"
                      :show-rating="false"
                      :rating="contrat.to.rating"
                    ></star-rating>
                  </div>
                </div>
              </div>
            </div>
          </header>

          <!-- Begin Tabs Details -->
          <section class="contrat-section">
            <!-- Begin Whole Project Tab -->
            <div v-if="contrat.type === 'Fixed Price' && !contrat.milestones" class="tabs-content">
              <div class="row-card">
                <div class="details-informations">
                  <h2>Budget</h2>
                  <span>
                    <money-format
                      :style="'display: inline-block;'"
                      :value="parseInt(contrat.amount)"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </div>
                <div class="details-informations">
                  <h2>Project Paid</h2>
                  <span>
                    <money-format
                      :style="'display: inline-block;'"
                      :value="parseInt(contrat.project_paid)"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </div>
                <div class="details-informations">
                  <h2>Total Earnings</h2>
                  <span>
                    <money-format
                      :style="'display: inline-block;'"
                      :value="parseInt(contrat.total_earnings)"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </div>
              </div>
            </div>
            <!-- End Whole Project Tab -->

            <!-- Begin Milestones Project Tab -->
            <div v-if="contrat.type === 'Fixed Price' && contrat.milestones" class="tabs-content">
              <div class="row-card">
                <div class="details-informations">
                  <h2>Budget</h2>
                  <span>
                    <money-format
                      :style="'display: inline-block;'"
                      :value="parseInt(contrat.amount)"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </div>
                <div class="details-informations">
                  <h2>Remaining</h2>
                  <span>
                    <money-format
                      :style="'display: inline-block;'"
                      :value="contrat.remaining"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </div>
                <div class="details-informations">
                  <h2>Milestones Paid</h2>
                  <span>
                    <money-format
                      :style="'display: inline-block;'"
                      :value="contrat.milestones_paid"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </div>
                <div class="details-informations">
                  <h2>Total Earnings</h2>
                  <span>
                    <money-format
                      :style="'display: inline-block;'"
                      :value="contrat.total_earnings"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </div>
              </div>
            </div>
            <!-- End Milestones Project Tab -->

            <!-- Begin Hourly Rate Project Tab -->
            <div v-if="contrat.type === 'Hourly Rate'" class="tabs-content">
              <div class="row-card">
                <div class="details-informations">
                  <h2>Hourly Rate</h2>
                  <span>
                    <money-format
                      :style="'display: inline-block;'"
                      :value="parseInt(contrat.rate)"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </div>
                <div class="details-informations">
                  <h2>Hours</h2>
                  <span>{{ String(contrat.work_hours).replace('.', ':') }} hrs</span>
                </div>
                <div class="details-informations">
                  <h2 v-if="user == contrat.to.hashid">Total Earnings</h2>
                  <h2 v-else>Total Spent</h2>
                  <span>
                    <money-format
                      :style="'display: inline-block;'"
                      :value="contrat.total_earnings"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </div>
              </div>
            </div>
            <!-- End Whole Project Tab -->
          </section>
          <!-- End Tabs Details -->

          <!-- Begin Milestones Project -->
          <section
            v-if="contrat.type === 'Fixed Price' && contrat.milestones"
            class="contrat-section"
          >
            <div class="row-card between milestones">
              <span>Milestones ({{ countInvoice }})</span>
              <button v-show="show" @click.prevent="toggleShow" class="dropdown-btn">
                <i class="icon-material-outline-keyboard-arrow-up"></i>
              </button>
              <button v-show="!show" @click.prevent="toggleShow" class="dropdown-btn">
                <i class="icon-material-outline-keyboard-arrow-down"></i>
              </button>
            </div>
            <slide-y-down-transition :duration="250">
              <div v-show="show">
                <section
                  v-for="(invoice, index) in contrat.invoices"
                  class="row-card between milestones-sub-section"
                  :key="index"
                >
                  <span>
                    {{ index + 1 }} {{ invoice.description }}
                    <br />
                    <span class="due-date">Due {{ moment(invoice.due_date).format('LL') }}</span>
                  </span>
                  <span class="money">
                    <money-format
                      :style="'display: inline-block;'"
                      :value="parseInt(invoice.amount)"
                      locale="en"
                      currency-code="USD"
                      subunit-value="true"
                      :hide-subunits="false"
                    ></money-format>
                  </span>
                </section>
              </div>
            </slide-y-down-transition>
          </section>
          <!-- End Milestones Project -->

          <!-- Begin Whole Project -->
          <section
            v-if="contrat.type === 'Fixed Price' && !contrat.milestones"
            class="contrat-section"
          >
            <div class="row-card between milestones">
              <span>Whole Project</span>
            </div>
            <section class="row-card between milestones-sub-section">
              <span>
                {{ contrat.title }}
                <br />
                <span class="due-date">Due {{ moment(contrat.due_date).format('LL') }}</span>
              </span>
              <span class="money">
                <money-format
                  :style="'display: inline-block;'"
                  :value="parseInt(contrat.amount)"
                  locale="en"
                  currency-code="USD"
                  subunit-value="true"
                  :hide-subunits="false"
                ></money-format>
              </span>
            </section>
          </section>
          <!-- End Whole Project -->

          <!-- Begin Screenshot section -->
          <!--<section v-if="contrat.type === 'Hourly Rate'" class="contrat-section">
                        <div class="row-card between milestones">
                            <span>Screenshots</span>
                            <button v-show="toggleScreenShot" @click.prevent="toggleScreenshot" class="dropdown-btn">
                                <i class="icon-material-outline-keyboard-arrow-up"></i>
                            </button>
                            <button v-show="!toggleScreenShot" @click.prevent="toggleScreenshot" class="dropdown-btn">
                                <i class="icon-material-outline-keyboard-arrow-down"></i>
                            </button>
                        </div>
                        <slide-y-down-transition :duration="250">
                            <section v-show="toggleScreenShot" class="row-card between milestones-sub-section">
                                    <div class="page">
                                        <div class="page__demo">
                                            <div class="main-container page__container">
                                                <div class="timeline">
                                                    <div class="timeline__group">
                                                        <span class="timeline__year">Fixing css for production</span>
                                                        <div class="timeline__box">
                                                            <div class="timeline__date">
                                                            <span class="timeline__day">8</span>
                                                            <span class="timeline__month">hrs</span>
                                                            </div>
                                                            <div class="timeline__post">
                                                                <div class="row">
                                                                    <div class="col-xl-2">
                                                                        <div class="timeline__content">
                                                                            <a target="_blank" href="#">
                                                                                <img width="100%" height="100%" src="data:image/jpeg;base64,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" alt="screenshot">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </section>
                        </slide-y-down-transition>
          </section>-->
          <!-- End Screenshot section -->

          <!-- Begin invoice section -->
          <section class="contrat-section">
            <div class="row-card invoices">
              <div class="row-card between milestones">
                <span>Invoices ({{ countInvoice }})</span>
                <button
                  v-show="showInvoices"
                  @click.prevent="toggleShowInvoices"
                  class="dropdown-btn"
                >
                  <i class="icon-material-outline-keyboard-arrow-up"></i>
                </button>
                <button
                  v-show="!showInvoices"
                  @click.prevent="toggleShowInvoices"
                  class="dropdown-btn"
                >
                  <i class="icon-material-outline-keyboard-arrow-down"></i>
                </button>
              </div>
              <slide-y-down-transition :duration="250">
                <ul v-show="showInvoices" class="dashboard-box-list">
                  <li v-for="(invoice, index) in contrat.invoices" :key="index">
                    <div class="invoice-list-item">
                      <strong>Invoice for: {{ invoice.description }}</strong>
                      <ul>
                        <li v-if="!invoice.paid_at">
                          <span class="unpaid">Unpaid</span>
                        </li>
                        <li v-else>
                          <span class="paid">Paid</span>
                        </li>
                        <li>Order: #{{ invoice.order }}</li>
                        <li>Date: {{ moment(invoice.created_at).format('LLLL') }}</li>
                      </ul>
                    </div>
                    <!-- Buttons -->
                    <div class="buttons-to-right">
                      <template v-if="contrat.type == 'Hourly Rate'">
                        <a
                          @click.prevent.stop="paidAndContinueContrat(contrat.hashid, invoice.hashid)"
                          v-if="contrat.work_hours >= 1 && !loading && !invoice.paid_at && contrat.from.hashid == user"
                          href="javascript:void;"
                          class="button"
                        >Paid &amp; Continue</a>
                        <a
                          @click.prevent.stop="paidAndEndContrat(contrat.hashid, invoice.hashid)"
                          v-if="contrat.work_hours >= 1 && !loading && !invoice.paid_at && contrat.from.hashid == user"
                          href="javascript:void;"
                          class="button"
                        >Paid &amp; End contrat</a>
                        <a
                          v-else-if="invoice.paid_at && !loading"
                          target="_blank"
                          :href="'/invoices/~' + invoice.hashid"
                          class="button gray"
                        >View Invoice</a>
                      </template>
                      <template v-if="contrat.type == 'Fixed Price'">
                        <button
                          @click.prevent.stop="finishPayment(invoice.hashid)"
                          v-if="!loading && !invoice.paid_at && contrat.from.hashid == user"
                          class="button"
                        >Finish Payment</button>
                        <a
                          v-else-if="invoice.paid_at && !loading"
                          target="_blank"
                          :href="'/invoices/~' + invoice.hashid"
                          class="button gray"
                        >View Invoice</a>
                      </template>
                    </div>
                    <half-circle-spinner
                      v-if="loading && invoiceIdentifier == invoice.hashid"
                      :animation-duration="1000"
                      :size="60"
                      color="#2a41e8"
                    />
                  </li>
                </ul>
              </slide-y-down-transition>
            </div>
          </section>
          <!-- End invoice section -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import { HalfCircleSpinner } from "epic-spinners";
import { SlideYDownTransition } from "vue2-transitions";
export default {
  props: ["contract", "user", "from"],
  data() {
    return {
      show: true,
      loading: false,
      countInvoice: 0,
      showInvoices: true,
      invoiceIdentifier: 0,
      contrat: this.contract,
      toggleScreenShot: true
    };
  },
  components: {
    HalfCircleSpinner,
    SlideYDownTransition
  },
  methods: {
    moment: moment,
    toggleScreenshot() {
      this.toggleScreenShot = !this.toggleScreenShot;
    },
    toggleShow() {
      this.show = !this.show;
    },
    toggleShowInvoices() {
      this.showInvoices = !this.showInvoices;
    },
    /**
     * Paid and end the hourly rate contrat.
     */
    async paidAndEndcontrat(contratId, invoiceId) {
      let _this = this;
      _this.loading = true;
      _this.invoiceIdentifier = invoiceId;
      await axios
        .get("/api/payment/end~" + contratId + "-" + invoiceId)
        .then(response => {
          window.location.href = response.data.payment_url;
        })
        .catch(error => {
          _this.loading = false;
        });
    },
    /**
     * Paid and continue the hourly rate contrat.
     */
    async paidAndContinuecontrat(contratId, invoiceId) {
      let _this = this;
      _this.loading = true;
      _this.invoiceIdentifier = invoiceId;
      await axios
        .get("/api/payment/continue~" + contratId + "-" + invoiceId)
        .then(response => {
          window.location.href = response.data.payment_url;
        })
        .catch(error => {
          _this.loading = false;
        });
    },
    async finishPayment(invoiceId) {
      let _this = this;
      _this.loading = true;
      _this.invoiceIdentifier = invoiceId;
      await this.axios
        .get("/api/payments/finish/~" + invoiceId)
        .then(response => {
          this.showNotification(response.data, 'success', true, 5000)
          setTimeout(() => {
            window.location.reload();
          }, 3000);
          _this.loading = false;
        })
        .catch(error => {
          this.showErrors(error)
          _this.loading = false;
        });
    }
  },
  mounted() {
    let _this = this;
    for (let i in this.contrat.invoices) {
      this.countInvoice += 1;
    }

    Echo.join("uplance")
      .listen("UserOnline", function(user) {
        if (_this.contrat.from.hashid == user.hashid) {
          _this.contrat.from.presence_status = user.presence_status;
          _this.contrat.from.switcher_status = user.switcher_status;
        }

        if (_this.contrat.to.hashid == user.hashid) {
          _this.contrat.to.presence_status = user.presence_status;
          _this.contrat.to.switcher_status = user.switcher_status;
        }
      })
      .listen("UserOffline", function(user) {
        if (_this.contrat.from.hashid == user.hashid) {
          _this.contrat.from.presence_status = user.presence_status;
          _this.contrat.from.switcher_status = user.switcher_status;
        }

        if (_this.contrat.to.hashid == user.hashid) {
          _this.contrat.to.presence_status = user.presence_status;
          _this.contrat.to.switcher_status = user.switcher_status;
        }
      });
  }
};
</script>
