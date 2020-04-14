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

          <div class="content with-padding padding-bottom-10">
            <div class="row">
              <div class="col-xl-12 col-lg-12 content-right-offset">
                <div class="single-page-header freelancer-header single-page-header-style">
                  <div class="single-page-header-inner">
                    <div class="left-side">
                      <template>
                        <div class="header-image freelancer-avatar">
                          <img :src="freelancer.avatar" alt="avatar" />
                        </div>
                        <div class="header-details">
                          <h3>
                            {{ freelancer.name }}
                            <span>{{ freelancer.tagline }}</span>
                          </h3>
                          <ul>
                            <li>
                              <div :data-rating="freelancer.rating" class="star-rating">
                                <star-rating
                                  :style="{position: 'relative', top: 3 + 'px'}"
                                  :star-size="18"
                                  :read-only="true"
                                  :show-rating="false"
                                  :rating="freelancer.rating"
                                ></star-rating>
                              </div>
                            </li>
                            <li style="position: relative; top: 7px;">
                              <img
                                class="flag"
                                :src="'/images/flags/' + String(freelancer.country).toLowerCase() + '.svg'"
                                alt
                              />
                              {{ getCountryName(freelancer.country) }}
                            </li>
                            <li v-if="freelancer.verified" style="position: relative; top: 7px;">
                              <div class="verified-badge-with-title">Verified</div>
                            </li>
                          </ul>
                        </div>
                      </template>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 content-right-offset">
                <div class="submit-field">
                  <h5>Contract title</h5>
                  <input v-model="contract_title" type="text" class="with-border" />
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
          <div class="headline">
            <h3>
              <span style="display: flex; justify-content: space-between;">
                <span>
                  <i class="icon-material-outline-business-center"></i>
                  <span>Terms</span>
                </span>
              </span>
            </h3>
          </div>
          <div class="content with-padding padding-bottom-50">
            <strong>Organize your project so that the work is done on time.</strong>
            <div class="payment margin-top-30">
              <div class="payment-tab payment-tab-active">
                <div class="payment-tab-trigger">
                  <input checked v-model="offer_type" id="hourly" type="radio" value="Hourly Rate" />
                  <label for="hourly">By hourly</label>
                </div>
                <div class="payment-tab-content margin-top-20">
                  <div class="row">
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>Hourly Rate</h5>
                        <p>The amount per hour that you set on the project.</p>
                        <money v-model="total_hourly" v-bind="money" class="with-border"></money>
                      </div>
                    </div>
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>The freelancer will receive</h5>
                        <p>
                          The freelancer will receive after the
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
                          The amount that will be taken from the freelancer as a
                          <a href="#">fee</a>.
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
              </div>
              <div class="payment-tab">
                <div class="payment-tab-trigger">
                  <input v-model="offer_type" id="milestone" type="radio" value="Milestone" />
                  <label for="milestone">By milestone</label>
                </div>
                <div class="payment-tab-content">
                  <p>Divide the project into milestones. For each milestone, you must first deposit a fund that will be retained until you approve the work.</p>

                  <br />

                  <strong>How many milestones do you want the freelancer to work?</strong>

                  <br />
                  <br />

                  <div
                    v-for="(milestone, index) in milestones"
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
                        <h5>The freelancer will receive</h5>
                        <p>
                          The freelancer will receive after the
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
              <div class="payment-tab">
                <div class="payment-tab-trigger">
                  <input v-model="offer_type" id="project" type="radio" value="Project" />
                  <label for="project">By project</label>
                </div>
                <div class="payment-tab-content">
                  <p>Deposit an amount for the whole project.</p>

                  <br />

                  <div class="row">
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>Total amount</h5>
                        <p>The total amount the freelancer will see on your offer.</p>
                        <money v-model="total_amount" v-bind="money" class="with-border"></money>
                      </div>
                    </div>
                    <div class="col-xl-4">
                      <div class="submit-field">
                        <h5>The freelancer will receive</h5>
                        <p>
                          The freelancer will receive after the
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
                        <p>What date do you want the project to be completed?</p>
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
              <i class="icon-material-outline-business-center"></i> Additional Details
            </h3>
          </div>

          <div class="content with-padding padding-bottom-10">
            <div class="row">
              <div class="col-xl-12">
                <div class="submit-field">
                  <h5>Offer description</h5>
                  <textarea v-model="description" cols="30" rows="5" class="with-border"></textarea>
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
                    >You can attach files that may be helpful for describing your offer.</span>
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
          @click.prevent.stop="makeAnOffer"
          v-if="submitButton"
          href="#"
          class="button ripple-effect big margin-top-30"
        >Make an offer</a>
        <half-circle-spinner v-if="loading" :animation-duration="1000" :size="60" color="#2a41e8" />
      </div>
    </div>
    <div class="margin-top-70"></div>
  </div>
</template>

<script>
import moment from "moment";
export default {
  props: ["freelancer", "proposal", "job"],
  data() {
    return {
      index: 0,
      loading: false,
      submitButton: true,
      attachments: [],
      cover_letter: "",
      payment_type: "milestone",
      milestones: [{ description: "", due_date: "", amount: 0 }],
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: false
      },
      contract_title: "",
      hourly_rate: 0,
      offer_type: "Hourly Rate",
      due_date: "",
      description: "",
      errors: [],

      service_fee: 5, // Percentage of service fee.

      fee_amount: 0,
      total_amount: 0,
      receive_amount: 0,

      total_hourly: 0,
      receive_hourly: 0,
      service_fee_hourly: 0
    };
  },
  watch: {
    milestones: {
      handler() {
        if (this.offer_type == "Milestone") {
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
    offer_type() {
      this.total_amount = 0;
      if (this.offer_type == "project") {
        this.milestones = [];
      } else {
        this.milestones = [{ description: "", due_date: "", amount: 0 }];
      }
    },
    errors() {
      if (this.errors) {
        for (let key in this.errors) {
          this.showNotification(this.errors[key], "error", true, 3000);
          this.errors.splice(key, 1);
        }
      }
    }
  },
  methods: {
    previewFreelancer() {
      window.open("/freelancers/~" + this.freelancer.hashid, "_blank");
    },
    fieldChange(e) {
      this.attachments = [];
      let vm = this;
      let selected_files = e.target.files;

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
    /*
     *  Checks whether the forms of the offer types are valid
     *  before sending the information to the servers.
     */
    checkForm() {
      if (!this.contract_title) {
        this.errors.push("You must choose the name of your contract.");
      }

      if (!this.offer_type) {
        this.errors.push("You must choose your type of offer.");
      } else {
        switch (this.offer_type) {
          case "Hourly Rate":
            if (!this.total_hourly) {
              this.errors.push(
                "You must set an hourly rate for your offer if you want to offer this type of offer."
              );
            } else {
              if (this.total_hourly < 5) {
                this.errors.push(
                  "Your hourly rate must be greater than or equal to $5."
                );
              } else if (this.total_hourly > 150) {
                this.errors.push(
                  "Your hourly rate must be less than or equal to $150."
                );
              }
            }
            break;
          case "Milestone":
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
            break;
          case "Project":
            if (!this.total_amount) {
              this.errors.push(
                "You must choose the total amount of your offer."
              );
            }

            if (this.total_amount > 0 && this.total_amount < 10) {
              this.errors.push(
                "The total amount of your offer must be greater than or equal to $10."
              );
            }

            if (!this.due_date) {
              this.errors.push(
                "You must set the due date of the whole project."
              );
            }
            break;
        }
      }

      if (!this.description) {
        this.errors.push("You must put a description of your offer.");
      }
    },
    async makeAnOffer() {
      this.checkForm();

      if (!this.errors.length) {
        let _this = this;
        _this.loading = true;
        _this.submitButton = false;

        let url = null;
        if (this.proposal) {
          url = `/api/offers/store/~${this.freelancer.hashid}?proposal=${this.proposal}`;
        } else {
          url = `/api/offers/store/~${this.freelancer.hashid}`;
        }

        let data = {
          contract_title: _this.contract_title,
          milestones: _this.milestones ? _this.milestones : null,
          hourly_rate: _this.total_hourly ? _this.total_hourly : null,
          total_amount: _this.total_amount ? _this.total_amount : null,
          description: _this.description,
          offer_type:
            _this.offer_type != "Hourly Rate"
              ? "Fixed Price"
              : _this.offer_type,
          to_id: _this.freelancer.hashid,
          due_date: _this.due_date
            ? moment(_this.due_date).format("YYYY-MM-DD")
            : null
        };

        await axios
          .post(url, data)
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
                    if (_this.proposal) {
                      window.location.href = `/proposals/list/~${_this.job}`;
                    } else {
                      window.location.href = `/freelancers`;
                    }
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
                if (_this.proposal) {
                  window.location.href = `/proposals/list/~${_this.job}`;
                } else {
                  window.location.href = `/freelancers`;
                }
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
.single-page-header-style:after,
.single-page-header-style:before {
  background: transparent !important;
}

.single-page-header-style {
  margin-bottom: 0 !important;
  padding: 30px 0 50px 0 !important;
  position: relative;
}
</style>
