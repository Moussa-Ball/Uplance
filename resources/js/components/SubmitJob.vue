<template>
  <div>
    <div class="dashboard-headline">
      <h3>Submit a Proposal</h3>
    </div>

    <div class="margin-top-70"></div>

    <div class="row">
      <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
          <div class="headline">
            <h3>
              <span style="display: flex; justify-content: space-between;">
                <span style="position: relative; top: 12px;">
                  <i class="icon-material-outline-gavel"></i>
                  <span v-if="user.credit > 1">{{ user.credit }} credits</span>
                  <span v-else-if="user.credit <= 1">{{ user.credit }} credit</span>
                </span>
              </span>
            </h3>
          </div>
          <div class="content with-padding padding-bottom-10">
            <div class="row">
              <div class="col-xl-12 col-lg-12 content-right-offset">
                This submission requires 2 credits.
                When you submit this job, you still have
                <span
                  v-if="user.credit > 1"
                >{{ user.credit - 2 }} credits.</span>
                <span v-else-if="user.credit <= 1">{{ user.credit }} credit.</span>
              </div>
            </div>
          </div>
        </div>
      </div>
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
                  Project Details
                </span>
                <span
                  @click.prevent.stop="viewJobPosting"
                  class="button apply-now-button"
                >View job posting</span>
              </span>
            </h3>
          </div>

          <div class="content with-padding padding-bottom-10">
            <div class="row">
              <div class="col-xl-12 col-lg-12 content-right-offset">
                <div class="single-page-section">
                  <h3 class="margin-bottom-25">{{ job.project_name }}</h3>
                  <div v-html="job.description" v-linkified:options="{ nl2br: true }"></div>
                </div>

                <div v-if="job.attachments.length" class="single-page-section">
                  <h3>Attachments</h3>
                  <div class="attachments-container">
                    <template v-for="(attachment, key) in job.attachments">
                      <a
                        :key="key"
                        target="_blank"
                        :href="attachment.file"
                        class="attachment-box ripple-effect"
                      >
                        <span>{{ attachment.name }}</span>
                        <i>{{ attachment.ext.toLowerCase() }}</i>
                      </a>
                    </template>
                  </div>
                </div>

                <!-- Skills -->
                <div v-if="job.skills" class="single-page-section">
                  <h3>Skills Required</h3>
                  <div class="task-tags">
                    <template v-for="(skill, key) in job.skills.split(',')">
                      <span :key="key">{{ skill }}</span>
                    </template>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="margin-top-70"></div>

    <div class="row">
      <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
          <!-- Headline -->
          <div class="headline">
            <h3>
              <span style="display: flex; justify-content:  space-between;">
                <span>
                  <i class="icon-material-outline-business-center"></i>
                  <span>Place a Bid</span>
                </span>
                <span
                  v-if="job.minimum == job.maximum"
                  style="float: right;"
                >{{ job.project_type }}: ${{ job.maximum }}</span>
                <span
                  v-else
                  style="float: right;"
                >{{ job.project_type }}: ${{ job.minimum }} - ${{ job.maximum }}</span>
              </span>
            </h3>
          </div>
          <div
            class="content with-padding padding-bottom-10"
            v-if="job.project_type == 'Hourly Rate'"
          >
            <div class="row">
              <div class="col-xl-4">
                <div class="submit-field">
                  <h5>Hourly Rate</h5>
                  <p>The total amount the customer will see for your proposal.</p>
                  <money v-model="total_hourly" v-bind="money" class="with-border"></money>
                </div>
              </div>
              <div class="col-xl-4">
                <div class="submit-field">
                  <h5>You'll receive</h5>
                  <p>
                    The amount you'll receive after the
                    <a href="#">service fees</a>.
                  </p>
                  <div>
                    <strong style="position: relative; top: 10px;">
                      <money-format
                        :style="'display: inline-block;'"
                        :value="receive_hourly"
                        locale="en"
                        currency-code="USD"
                        subunit-value="true"
                        :hide-subunits="false"
                      ></money-format>
                    </strong>
                  </div>
                </div>
              </div>
              <div class="col-xl-4">
                <div class="submit-field">
                  <h5>Uplance Service Fee</h5>
                  <p>
                    The estimated you'll receive after the
                    <a href="#">service fees</a>.
                  </p>
                  <div>
                    <strong style="position: relative; top: 10px;">
                      -
                      <money-format
                        :style="'display: inline-block;'"
                        :value="service_fee_hourly"
                        locale="en"
                        currency-code="USD"
                        subunit-value="true"
                        :hide-subunits="false"
                      ></money-format>/ hr
                    </strong>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            class="content with-padding padding-bottom-50"
            v-if="job.project_type == 'Fixed Price'"
          >
            <strong>How do you want to be paid ?</strong>
            <div class="payment margin-top-30">
              <div class="payment-tab payment-tab-active">
                <div class="payment-tab-trigger">
                  <input
                    id="paypal"
                    v-model="payment_type"
                    name="payment_type"
                    type="radio"
                    value="project"
                  />
                  <label for="paypal">By project</label>
                </div>

                <div class="payment-tab-content">
                  <p>Get your payment when all the work is done and approved.</p>

                  <br />

                  <div class="row">
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>The amount for this job</h5>
                        <p>The total amount the customer will see for your proposal.</p>
                        <money v-model="total_amount" v-bind="money" class="with-border"></money>
                      </div>
                    </div>
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>You'll receive</h5>
                        <p>
                          The amount you'll receive after the
                          <a href="#">service fees</a>.
                        </p>
                        <strong style="position: relative; top: 10px;">
                          <money-format
                            :style="'display: inline-block;'"
                            :value="receive_amount"
                            locale="en"
                            currency-code="USD"
                            subunit-value="true"
                            :hide-subunits="false"
                          ></money-format>
                        </strong>
                      </div>
                    </div>
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>Uplance Service Fee</h5>
                        <p>
                          The estimated amount for the
                          <a href="#">service fees</a>.
                        </p>
                        <div>
                          <strong style="position: relative; top: 10px;">
                            -
                            <money-format
                              :style="'display: inline-block;'"
                              :value="fee_amount"
                              locale="en"
                              currency-code="USD"
                              subunit-value="true"
                              :hide-subunits="false"
                            ></money-format>
                          </strong>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-12">
                      <div class="submit-field">
                        <h5>Due date</h5>
                        <p>Indicate the due date of the whole project.</p>
                        <div>
                          <strong style="position: relative; top: 10px;">
                            <datetime v-model="due_date" class="theme-uplance" format="yyyy-MM-dd"></datetime>
                          </strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="payment-tab">
                <div class="payment-tab-trigger">
                  <input
                    checked
                    id="milestone"
                    v-model="payment_type"
                    name="payment_type"
                    type="radio"
                    value="milestones"
                  />
                  <label for="milestone">By milestone</label>
                </div>
                <div class="payment-tab-content">
                  <p>Divide the project into milestones. You will be paid for the milestones as they are completed and approved.</p>

                  <br />

                  <strong>How many milestones are you working on?</strong>

                  <br />
                  <br />

                  <div
                    v-for="(milestone, index) in milestones"
                    :key="index"
                    style="display: flex; justify-content: space-between; flex-flow: row wrap;"
                  >
                    <strong
                      v-if="index == 0"
                      style="margin: auto 2px; position: relative; bottom: 3px;"
                    >{{ index + 1 }}</strong>
                    <strong
                      v-if="index > 0"
                      :style="'margin-top: auto; margin-bottom: auto; margin-right: 15px;'"
                      style="position: relative; bottom: 3px;"
                    >{{ index + 1 }}</strong>

                    <div class="submit-field">
                      <h5>Description</h5>
                      <input v-model="milestone.description" type="text" class="with-border" />
                    </div>

                    <div class="submit-field">
                      <h5>Due date</h5>
                      <!--<input v-model="milestone.due_date" type="text" class="with-border">-->
                      <datetime
                        v-model="milestone.due_date"
                        class="theme-uplance"
                        format="yyyy-MM-dd"
                      ></datetime>
                    </div>

                    <div class="submit-field">
                      <h5>Amount</h5>
                      <!--<input v-model="milestone.amount" type="text" class="with-border">-->
                      <money v-model="milestone.amount" v-bind="money" class="with-border"></money>
                    </div>

                    <i
                      v-if="index > 0"
                      @click.prevent="removeMilestone(index)"
                      class="icon-line-awesome-close"
                      style="margin: auto 0; position: relative; bottom: 3px; cursor: pointer;"
                    ></i>
                  </div>

                  <a
                    @click.prevent.stop="addMilestone"
                    href="#"
                    class="add_milestone_link"
                  >+ Add milestone</a>

                  <div class="line-milestone"></div>

                  <div class="row">
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>The amount for this job</h5>
                        <p>The total amount the customer will see for your proposal.</p>
                        <div>
                          <strong style="position: relative; top: 10px;">
                            <money-format
                              :value="total_amount"
                              locale="en"
                              currency-code="USD"
                              subunit-value="true"
                              :hide-subunits="false"
                            ></money-format>
                          </strong>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>You'll receive</h5>
                        <p>
                          The amount you'll receive after the
                          <a href="#">service fees</a>.
                        </p>
                        <div>
                          <strong style="position: relative; top: 10px;">
                            <money-format
                              :value="receive_amount"
                              locale="en"
                              currency-code="USD"
                              subunit-value="true"
                              :hide-subunits="false"
                            ></money-format>
                          </strong>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>Uplance Service Fee</h5>
                        <p>
                          The estimated amount for the
                          <a href="#">service fees</a>.
                        </p>
                        <div>
                          <strong style="position: relative; top: 10px;">
                            -
                            <money-format
                              :style="'display: inline-block;'"
                              :value="fee_amount"
                              locale="en"
                              currency-code="USD"
                              subunit-value="true"
                              :hide-subunits="false"
                            ></money-format>
                          </strong>
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
    </div>

    <div class="margin-top-70"></div>

    <div class="row">
      <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
          <!-- Headline -->
          <div class="headline">
            <h3>
              <i class="icon-material-outline-business-center"></i> Describe Your Proposal
            </h3>
          </div>

          <div class="content with-padding padding-bottom-10">
            <div class="row">
              <div class="col-xl-12">
                <div class="submit-field">
                  <h5>Cover Letter</h5>
                  <textarea v-model="cover_letter" cols="30" rows="5" class="with-border"></textarea>
                  <div class="uploadButton margin-top-30">
                    <input
                      @change="fieldChange"
                      class="uploadButton-input"
                      type="file"
                      accept="image/*, application/pdf"
                      id="upload"
                      multiple
                    />
                    <label class="uploadButton-button ripple-effect" for="upload">Upload Files</label>
                    <span
                      class="uploadButton-file-name"
                    >You can attach files that may be helpful for describing your proposal.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-12">
        <div v-if="loading" class="margin-top-30"></div>
        <a
          @click.prevent.stop="submitProposal"
          v-if="submitButton"
          href="#"
          class="button ripple-effect big margin-top-30"
        >Submit a Proposal</a>
        <half-circle-spinner v-if="loading" :animation-duration="1000" :size="60" color="#2a41e8" />
      </div>
    </div>

    <div class="margin-top-70"></div>
  </div>
</template>

<script>
export default {
  props: ["job", "user", "url"],
  data() {
    return {
      index: 0,
      due_date: "",
      loading: false,
      submitButton: true,
      attachments: [],
      cover_letter: "",
      payment_type: "project",
      milestones: [],
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: false
      },

      service_fee: 5, // Percentage of service fee.

      fee_amount: 0,
      total_amount: 0,
      receive_amount: 0,

      total_hourly: 0,
      receive_hourly: 0,
      service_fee_hourly: 0,

      errors: []
    };
  },
  watch: {
    milestones: {
      handler() {
        if (this.payment_type === "milestones") {
          this.total_amount = 0;
          for (let index in this.milestones) {
            if (this.milestones.hasOwnProperty(index)) {
              this.total_amount += this.milestones[index].amount;
            }
          }
        }
      },
      deep: true
    },
    total_amount() {
      if (this.total_amount) {
        if (this.total_amount >= 5 || this.total_amount <= 10) {
          this.fee_amount = (this.total_amount / 100) * 20;
          this.receive_amount = this.total_amount - this.fee_amount;
        }

        if (this.total_amount >= 10 || this.total_amount <= 20) {
          this.fee_amount = (this.total_amount / 100) * 10;
          this.receive_amount = this.total_amount - this.fee_amount;
        }

        if (this.total_amount >= 20) {
          this.fee_amount = (this.total_amount / 100) * 5;
          this.receive_amount = this.total_amount - this.fee_amount;
        }
      } else {
        this.fee_amount = 0;
        this.total_amount = 0;
        this.receive_amount = 0;
      }
    },
    total_hourly() {
      if (this.total_hourly >= 5 || this.total_hourly <= 10) {
        this.service_fee_hourly = (this.total_hourly / 100) * 20;
        this.receive_hourly = this.total_hourly - this.service_fee_hourly;
      }

      if (this.total_hourly >= 10 || this.total_hourly <= 20) {
        this.service_fee_hourly = (this.total_hourly / 100) * 10;
        this.receive_hourly = this.total_hourly - this.service_fee_hourly;
      }

      if (this.total_hourly >= 20) {
        this.service_fee_hourly = (this.total_hourly / 100) * 5;
        this.receive_hourly = this.total_hourly - this.service_fee_hourly;
      }
    },
    payment_type() {
      this.total_amount = 0;
      if (this.payment_type == "project") {
        this.milestones = [];
      } else {
        this.milestones = [{ description: "", due_date: "", amount: 0 }];
      }
    },
    errors() {
      if (this.errors) {
        for (let key in this.errors) {
          new Noty({
            text: "<strong>" + this.errors[key] + "</strong>",
            type: "error",
            theme: "metroui",
            progressBar: true,
            timeout: 3000
          }).show();
          this.errors.splice(key, 1);
        }
      }
    }
  },
  methods: {
    fieldChange(e) {
      let selected_files = e.target.files;
      this.attachments = [];

      if (!selected_files.length) return false;
      for (let i = 0; i < selected_files.length; i++) {
        this.attachments.push(selected_files[i]);
      }
    },
    addMilestone() {
      this.milestones.push({ description: "", due_date: "", amount: 0 });
    },
    removeMilestone(index) {
      this.milestones.splice(index, 1);
    },
    viewJobPosting() {
      window.open(this.url, "_blank");
    },
    /*------------------------------------------------------------
     *  Checks whether the forms of the offer types are valid
     *  before sending the information to the servers.
     *-----------------------------------------------------------*/
    checkForm() {
      switch (this.job.project_type) {
        case "Hourly Rate":
          if (this.total_hourly < 5) {
            this.errors.push(
              "The total hourly rate must be greater than or equal to $5."
            );
          }
          if (this.total_hourly > 150) {
            this.errors.push("The Total hourly rate must not exceed $150");
          }
          break;
        case "Fixed Price":
          if (this.payment_type === "milestones") {
            for (let key in this.milestones) {
              if (this.milestones.hasOwnProperty(key)) {
                if (!this.milestones[key].description) {
                  let key_one = key;
                  ++key_one;
                  this.errors.push(
                    "The description of milestone " + key_one + " is invalid."
                  );
                }
                if (!this.milestones[key].due_date) {
                  let key_two = key;
                  ++key_two;
                  this.errors.push(
                    "The due date for milestone " + key_two + " is invalid."
                  );
                }
                if (!this.milestones[key].amount) {
                  let key_three = key;
                  ++key_three;
                  this.errors.push(
                    "The amount of milestone " + key_three + " is invalid."
                  );
                } else if (
                  this.milestones[key].amount > 0 &&
                  this.milestones[key].amount < 5
                ) {
                  this.errors.push(
                    "The amount of milestone " +
                      ++key +
                      " must be greater than or equal to $5."
                  );
                }
              }
            }
          } else if (this.payment_type === "project") {
            if (this.total_amount < 5) {
              this.errors.push(
                "The total amount must be greater than or equal to $5."
              );
            }
            if (this.total_amount > 100000) {
              this.errors.push(
                "The total amount must not be greater to $100.000."
              );
            }
            if (!this.due_date) {
              this.errors.push(
                "You must indicate the due date for the whole project."
              );
            }
          }
          break;
      }

      if (!this.cover_letter) {
        this.errors.push("You must put a cover letter for your proposal.");
      }
    },
    async submitProposal() {
      if (this.user.credit <= 1) {
        this.showNotification(
          "Sorry, you do not have enough credit to submit this proposal.",
          "warning",
          true,
          3000
        );
      }

      this.checkForm();

      if (!this.errors.length) {
        this.loading = true;
        this.submitButton = false;

        // Sending proposal
        await this.axios
          .post(`/api/proposals/~${this.job.id}`, {
            proposal_type: this.job.project_type,
            hourly_amount:
              this.job.project_type == "Hourly Rate" ? this.total_hourly : null,
            fixed_amount:
              this.job.project_type == "Fixed Price" ? this.total_amount : null,
            cover_letter: this.cover_letter,
            milestones:
              this.job.project_type == "Fixed Price" &&
              this.payment_type != "project"
                ? this.milestones
                : null,
            due_date:
              this.job.project_type == "Fixed Price" &&
              this.payment_type == "project"
                ? this.due_date
                : null
          })
          .then(response => {
            if (this.attachments.length) {
              let formData = new FormData();
              for (let i = 0; i < this.attachments.length; i++) {
                formData.append("files[]", this.attachments[i]);
              }
              formData.append("attachable_type", response.data.attachable_type);
              formData.append("attachable_id", response.data.attachable_id);
              const config = {
                headers: { "content-type": "multipart/form-data" }
              };
              this.axios
                .post("/api/attachments", formData, config)
                .then(res => {
                  this.showNotification(
                    response.data.message,
                    "success",
                    true,
                    5000
                  );
                  this.loading = false;
                  setTimeout(() => {
                    window.location.href = "/";
                  }, 2000);
                })
                .catch(err => {
                  this.loading = false;
                  this.showErrors(err);
                });
            } else {
              this.showNotification(
                response.data.message,
                "success",
                true,
                5000
              );
              this.loading = false;
              setTimeout(() => {
                window.location.href = "/";
              }, 2000);
            }
          })
          .catch(error => {
            this.loading = false;
            this.submitButton = true;
            this.showErrors(error);
          });
      }
    }
  }
};
</script>

<style>
.add_milestone_link {
  color: #2a41e8;
}

.add_milestone_link:hover {
  color: #2a41e8;
  text-decoration: underline;
}

.payment-tab.payment-tab-active {
  max-height: 100% !important;
  background: #fff;
}

.vdatetime-input {
  border: 1px solid #e0e0e0 !important;
  box-shadow: 0 1px 4px 0px rgba(0, 0, 0, 0.05) !important;
}

@media screen and (min-width: 992px) {
  .vdatetime-popup {
    top: 55% !important;
    left: 60% !important;
  }
}

.theme-uplance .vdatetime-popup__header,
.theme-uplance .vdatetime-calendar__month__day--selected > span > span,
.theme-uplance .vdatetime-calendar__month__day--selected:hover > span > span {
  background: #2a41e8;
}

.theme-uplance .vdatetime-year-picker__item--selected,
.theme-uplance .vdatetime-time-picker__item--selected,
.theme-uplance .vdatetime-popup__actions__button {
  color: #2a41e8;
}

.line-milestone {
  border-top: 1px solid rgba(0, 0, 0, 0.05);
  padding: 10px 0;
  height: 1px;
  width: 100%;
}
</style>
