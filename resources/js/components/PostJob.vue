<template>
  <div>
    <!-- Dashboard Headline -->
    <div class="dashboard-headline">
      <h3>Post a Job</h3>
    </div>
    <div class="row">
      <!-- Dashboard Box -->
      <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
          <!-- Headline -->
          <div class="headline">
            <h3>
              <i class="icon-feather-folder-plus"></i> Post a Job
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
                  <h5>
                    What is your estimated budget?
                    <i
                      class="help-icon"
                      data-tippy-placement="right"
                      title="You cannot change the project type after you publish it, unless you delete it and publish another one."
                    ></i>
                  </h5>
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
                  <div class="feedback-yes-no margin-top-0">
                    <div class="radio">
                      <input
                        v-model="job.project_type"
                        value="Fixed Price"
                        id="radio-1"
                        name="project_type"
                        type="radio"
                        checked
                      />
                      <label for="radio-1">
                        <span class="radio-label"></span> Fixed Price Project
                      </label>
                    </div>

                    <div class="radio">
                      <input
                        v-model="job.project_type"
                        value="Hourly Rate"
                        id="radio-2"
                        name="project_type"
                        type="radio"
                      />
                      <label for="radio-2">
                        <span class="radio-label"></span> Hourly Project
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-6">
                <div class="submit-field">
                  <h5>
                    Freelancer location
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
                    >Images or documents that might be helpful in describing your project</span>
                  </div>
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
          @click.prevent.stop="postJob"
          class="button ripple-effect big margin-top-30 bg-white"
        >
          <i class="icon-feather-plus"></i> Post a Job
        </button>
        <half-circle-spinner v-if="loading" :animation-duration="1000" :size="60" color="#2a41e8" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      job: {},
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
    "job.skills": function() {
      if (this.user.skills) {
        let skills = this.user.skills;
        skills = skills.split(",");
        for (let i in skills) {
          this.skills.push({ text: skills[i], tiClasses: ["ti-valid"] });
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
  computed: {
    filteredItems() {
      return this.autocompleteSkills.filter(i => {
        return i.text.toLowerCase().indexOf(this.skill.toLowerCase()) !== -1;
      });
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
    async postJob() {
      this.checkForm();
      if (!this.errors.length) {
        this.loading = true;
        this.job.skills = this.skills_list;
        await this.axios
          .post("/api/jobs/store", this.job)
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
                  this.posted = true;
                  this.showNotification(
                    response.data.message,
                    "success",
                    true,
                    5000
                  );
                  this.loading = false;
                  setTimeout(() => {
                    window.location.href = "/jobs/manage";
                  }, 2000);
                })
                .catch(err => {
                  this.showErrors(err);
                });
            } else {
              this.posted = true;
              this.showNotification(
                response.data.message,
                "success",
                true,
                5000
              );
              this.loading = false;
              setTimeout(() => {
                window.location.href = "/jobs/manage";
              }, 2000);
            }
          })
          .catch(error => {
            this.showErrors(error);
          });
      }
    }
  },
  async mounted() {
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
