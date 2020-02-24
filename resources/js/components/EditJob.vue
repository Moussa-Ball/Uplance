<template>
  <div>
    <!-- Dashboard Headline -->
    <div class="dashboard-headline">
      <h3>Edit Your Project</h3>
      <span class="margin-top-7">Project: {{ job.project_name }}</span>
    </div>
    <div class="row">
      <!-- Dashboard Box -->
      <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
          <!-- Headline -->
          <div class="headline">
            <h3>
              <i class="icon-feather-edit"></i> Edit Job
            </h3>
          </div>

          <div class="content with-padding padding-bottom-10">
            <div class="row">
              <div class="col-xl-6">
                <div class="submit-field">
                  <h5>Project Name</h5>
                  <input
                    v-model="job.project_name"
                    type="text"
                    class="with-border"
                    placeholder="e.g. build me a website"
                  />
                </div>
              </div>

              <div class="col-xl-6">
                <div class="submit-field">
                  <h5>Category</h5>
                  <multiselect
                    v-model="job.category"
                    :options="options"
                    :searchable="false"
                    :close-on-select="true"
                    :show-labels="false"
                    placeholder="Choose category"
                  ></multiselect>
                </div>
              </div>

              <div class="col-xl-6">
                <div class="submit-field">
                  <h5>What is your estimated budget?</h5>
                  <div class="row">
                    <div class="col-xl-6">
                      <div class="input-with-icon">
                        <money v-model="job.minimum" v-bind="money" class="with-border"></money>
                      </div>
                    </div>
                    <div class="col-xl-6">
                      <div class="input-with-icon">
                        <money v-model="job.maximum" v-bind="money" class="with-border"></money>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-6">
                <div class="submit-field">
                  <h5>
                    Freelancer location
                    <span>(Not required)</span>
                    <i
                      class="help-icon"
                      data-tippy-placement="right"
                      title="You can leave the field blank if you don't want to specify the freelancer location."
                    ></i>
                  </h5>
                  <div class="input-with-icon">
                    <places
                      v-model="job.location"
                      @change="val => { job.location = val.name }"
                      placeholder="eg: Canada or New York"
                    ></places>
                  </div>
                </div>
              </div>

              <div class="col-xl-12">
                <div class="submit-field">
                  <h5>What skills are required?</h5>
                  <div class="keywords-container">
                    <div class="keyword-input-container">
                      <tags-input
                        element-id="tags"
                        v-model="skills"
                        placeholder="e.g. PHP or CSS3"
                        :existing-tags="autocompleteSkills"
                        :typeahead="true"
                      ></tags-input>
                    </div>
                    <div class="keywords-list"></div>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>

              <div class="col-xl-12" style="margin-top: 40px;">
                <div class="submit-field">
                  <h5>Describe Your Project</h5>
                  <textarea v-model="job.description" cols="30" rows="5" class="with-border"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div v-if="loading" class="margin-top-30"></div>
        <button
          v-if="!loading && !posted"
          @click.prevent.stop="editJob"
          class="button ripple-effect big margin-top-30"
        >
          <i class="icon-feather-edit-2 margin-right-10"></i>
          <span>Edit Job</span>
        </button>
        <half-circle-spinner v-if="loading" :animation-duration="1000" :size="60" color="#2a41e8" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"],
  data() {
    return {
      job: this.data,
      skill: "",
      skills: [],
      errors: [],
      options: [],
      posted: false,
      loading: false,
      skills_list: "",
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: false
      },
      attachments: [],
      autocompleteSkills: []
    };
  },
  watch: {
    errors() {
      if (this.errors) {
        for (let key in this.errors) {
          new Noty({
            text: "<strong>" + this.errors[key] + "</strong>",
            type: "error",
            theme: "metroui",
            progressBar: true,
            timeout: 5000
          }).show();
          this.errors.splice(key, 1);
        }
      }
    },
    skills() {
      let separator = ",";
      let implodedArray = "";

      for (let i = 0; i < this.skills.length; i++) {
        // add a string from original array
        implodedArray += this.skills[i].value;

        // unless the iterator reaches the end of
        // the array add the separator string
        if (i != this.skills.length - 1) {
          implodedArray += separator;
        }
      }
      this.skills_list = implodedArray;
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
    checkForm() {
      if (!this.job.project_name) {
        this.errors.push("The project name field is required.");
      }

      if (!this.job.category) {
        this.errors.push("The category field is required.");
      }

      if (!this.job.project_type) {
        this.errors.push("You must choose the type of project.");
      } else {
        if (this.job.project_type === "Hourly Rate") {
          if (this.job.minimum < 5) {
            this.errors.push(
              "The minimum hourly rate must be greater than or equal to $5."
            );
          }

          if (this.job.minimum >= 5 && this.job.minimum > this.job.maximum) {
            this.errors.push(
              "The maximum hourly rate must be greater than or equal to the minimum hourly rate."
            );
          }

          if (this.job.maximum > 150) {
            this.errors.push(
              "The maximum hourly rate must be less than or equal to $150."
            );
          }
        } else if (this.job.project_type === "Fixed Price") {
          if (this.job.minimum < 5) {
            this.errors.push(
              "The minimum amount must be greater than or equal to $5."
            );
          }

          if (this.job.minimum >= 5 && this.job.minimum > this.job.maximum) {
            this.errors.push(
              "The maximum amount must be greater than or equal to the minimum amount."
            );
          }

          if (this.job.maximum > 100000) {
            this.errors.push(
              "The maximum hourly rate must be less than or equal to $100.000."
            );
          }
        }
      }

      if (!this.skills_list) {
        this.errors.push("The skills field is required.");
      }

      if (!this.job.description) {
        this.errors.push("The description field is required.");
      }
    },
    async editJob() {
      this.checkForm();
      if (!this.errors.length) {
        this.loading = true;
        this.job.skills = this.skills_list;
        await this.axios
          .put(`/api/jobs/update/~${this.job.id}`, this.job)
          .then(response => {
            this.posted = true;
            this.showNotification(response.data, "success", true, 5000);
            setTimeout(() => {
              window.location.href = "/jobs/manage";
            }, 2000);
          })
          .catch(error => {
            this.showErrors(error);
          });
        this.loading = false;
      }
    }
  },
  async mounted() {
    if (this.job.skills) {
      let skills = this.job.skills;
      skills = skills.split(",");
      for (let i in skills) {
        this.skills.push({ key: skills[i], value: skills[i] });
      }
    }

    let _this = this;
    this.loading = true;
    await this.axios
      .get("/api/profile/settings/categories")
      .then(response => {
        response.data.data.filter(function(category) {
          _this.options.push(category.name);
        });
      })
      .catch(error => {
        this.showErrors(error);
      });

    await this.axios
      .get("/api/profile/settings/skills")
      .then(response => {
        response.data.data.filter(function(skill) {
          _this.autocompleteSkills.push({ key: skill.name, value: skill.name });
        });
      })
      .catch(error => {
        this.showErrors(error);
      });
    this.loading = false;
  }
};
</script>

<style lang="scss">
.tags-input-wrapper-default.tags-input {
  height: auto !important;
  input {
    box-shadow: none !important;
    margin-bottom: 0;
  }
}
</style>
