<template>
  <div>
    <div class="margin-top-90 margin-bottom-90">
      <div class="container">
        <div class="row">
          <div class="col-xl-3 col-lg-4">
            <div class="sidebar-container">
              <div class="sidebar-widget">
                <h3>Location</h3>
                <div class="input-with-icon">
                  <multiselect
                    class="search-countries"
                    v-model="location"
                    :options="countries"
                    :searchable="true"
                    :show-labels="false"
                    placeholder="Location"
                  ></multiselect>
                </div>
              </div>

              <div class="sidebar-widget">
                <h3>Category</h3>
                <multiselect
                  v-model="category"
                  :options="options"
                  :searchable="false"
                  :close-on-select="true"
                  :show-labels="false"
                  placeholder="Choose category"
                ></multiselect>
              </div>

              <div class="sidebar-widget">
                <h3>Budget</h3>
                <div class="input-with-icon" @keyup.enter="getResults()">
                  Min
                  <money v-model="minimum" v-bind="money" class="with-border"></money>
                </div>
                <div class="margin-top-20"></div>
                <div class="input-with-icon" @keyup.enter="getResults()">
                  Max
                  <money v-model="maximum" v-bind="money" class="with-border"></money>
                </div>
              </div>

              <div class="sidebar-widget">
                <h3>Project Type</h3>
                <div class="checkbox">
                  <input
                    v-model="fixed_price"
                    value="Fixed Price"
                    type="checkbox"
                    id="fixed"
                    checked
                  />
                  <label for="fixed">
                    <span class="checkbox-icon"></span> Fixed Projects
                  </label>
                </div>
                <div class="margin-top-15"></div>
                <div class="checkbox">
                  <input
                    v-model="hourly_rate"
                    value="Hourly Rate"
                    type="checkbox"
                    id="hourly"
                    checked
                  />
                  <label for="hourly">
                    <span class="checkbox-icon"></span> Hourly Projects
                  </label>
                </div>
              </div>

              <div class="sidebar-widget">
                <h3>Skills</h3>
                <tags-input
                  element-id="tags"
                  v-model="skills"
                  placeholder="e.g. PHP or CSS3"
                  :existing-tags="autocompleteSkills"
                  :typeahead="true"
                ></tags-input>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
          <div class="col-xl-9 col-lg-8 content-left-offset">
            <h3 v-if="jobs.total" class="page-title">Search Results: {{ jobs.total }}</h3>
            <h3 v-else class="page-title">Search Results: 0</h3>

            <div class="margin-top-15">
              <div class="switch-container" style="width: 100% !important;">
                <div class="row">
                  <div class="col-xl-8">
                    <input
                      v-on:keyup.enter="getResults"
                      v-model="query"
                      type="text"
                      class="keyword-input"
                      placeholder="Search"
                    />
                  </div>
                  <div class="col-xl-4">
                    <select
                      v-model="sort_by"
                      class="selectpicker default"
                      data-selected-text-format="count"
                      data-size="7"
                      title="All Categories"
                    >
                      <option value="Newest" selected>Newest</option>
                      <option value="Oldest">Oldest</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div
              v-if="jobs.total === 0 && !skeletonLoading"
              class="tasks-list-container margin-top-35"
            >
              <h1 class="page-title">Sorry, we did not find a job.</h1>
            </div>

            <div v-if="loadingNewJob" class="tasks-list-container margin-top-35">
              <half-circle-spinner :animation-duration="1000" :size="60" color="#2a41e8" />
            </div>

            <div v-if="hasJob" class="tasks-list-container margin-top-35">
              <button @click="getRecentJob" class="button-see-more-job">
                <span v-if="jobIndex == 1">A new job has just been published. Click to see it.</span>
                <span v-else-if="jobIndex > 1">New jobs are published. Click to see them.</span>
              </button>
            </div>

            <div
              v-for="(i, key) in 3"
              :key="key"
              class="tasks-list-container compact-list margin-top-35 margin-bottom-60"
            >
              <ContentLoader v-if="skeletonLoading">
                <rect x="20" y="30" rx="0" ry="0" width="30%" height="15" />
                <rect x="20" y="60" rx="0" ry="0" width="60%" height="15" />
                <rect x="20" y="90" rx="0" ry="0" width="90%" height="15" />
              </ContentLoader>
            </div>

            <div v-if="!skeletonLoading" class="tasks-list-container compact-list margin-top-35">
              <template v-for="(job, key) in jobs.data">
                <a target="_blank" :href="`jobs/~${job.hashid}`" class="task-listing" :key="key">
                  <div class="task-listing-details">
                    <div class="task-listing-description">
                      <h3 class="task-listing-title">{{ job.project_name }}</h3>
                      <ul class="task-icons">
                        <li>
                          <i class="icon-material-outline-location-on"></i>
                          {{ job.country }}
                        </li>
                        <li>
                          <i class="icon-material-outline-access-time"></i>
                          <vue-moments-ago
                            style="font-size: 16px;"
                            :date="job.created_at"
                            lang="en"
                            :prefix="''"
                            suffix="ago"
                          ></vue-moments-ago>
                        </li>
                      </ul>
                      <p class="task-listing-text">
                        <read-more
                          class="job_description"
                          more-str="read more"
                          :text="job.description"
                          link="#"
                          less-str="read less"
                          :max-chars="120"
                        ></read-more>
                      </p>
                      <div class="task-tags">
                        <template v-for="(skill, key) in getSkillsInArray(job.skills)">
                          <span :key="key">{{ skill }}</span>
                        </template>
                      </div>
                    </div>
                  </div>
                  <div class="margin-top-10"></div>
                  <div class="task-listing-bid">
                    <div class="task-listing-bid-inner">
                      <div class="task-offers">
                        <strong v-if="job.minimum == job.maximum">
                          <money-format
                            :style="'display: inline-block;'"
                            :value="convertStringToInt(job.maximum)"
                            locale="en"
                            currency-code="USD"
                            subunit-value="true"
                            :hide-subunits="true"
                          ></money-format>
                        </strong>
                        <strong v-else>
                          <money-format
                            :style="'display: inline-block;'"
                            :value="convertStringToInt(job.minimum)"
                            locale="en"
                            currency-code="USD"
                            subunit-value="true"
                            :hide-subunits="true"
                          ></money-format>
                          <span :style="'display: inline-block;'">-</span>
                          <money-format
                            :style="'display: inline-block;'"
                            :value="convertStringToInt(job.maximum)"
                            locale="en"
                            currency-code="USD"
                            subunit-value="true"
                            :hide-subunits="true"
                          ></money-format>
                        </strong>
                        <span>{{ job.project_type }}</span>
                      </div>
                      <a
                        :to="{ name: 'preview-job', params: { lang: 'en', slug: job.slug, id: job.hashid }}"
                      >
                        <span class="button button-sliding-icon ripple-effect">
                          See more
                          <i class="icon-material-outline-arrow-right-alt"></i>
                        </span>
                      </a>
                    </div>
                  </div>
                </a>
              </template>
            </div>
            <div class="clearfix"></div>
            <div v-if="jobs" class="row">
              <div class="col-md-12">
                <div class="pagination-container margin-top-60 margin-bottom-60">
                  <nav class="pagination">
                    <pagination :limit="5" :data="jobs" @pagination-change-page="getResults"></pagination>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loadingNewJob: false,
      show: false,
      location: "",
      category: "",
      options: [],
      countries: [],
      minimum: 0,
      maximum: 0,
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: false
      },
      fixed_price: "",
      hourly_rate: "",
      skill: "",
      skills: [],
      autocompleteSkills: [],
      jobIndex: 0,
      hasJob: false,
      project_type: "",
      skeletonLoading: false,
      jobs: {},
      query: "",
      skills_list: "",
      sort_by: "Newest"
    };
  },
  computed: {
    countryNameFromDigitCode(country_code) {
      return this.countryList.getName(country_code);
    },
    // eslint-disable-next-line vue/return-in-computed-property
    filteredItems() {
      return this.autocompleteSkills.filter(i => {
        return i.text.toLowerCase().indexOf(this.skill.toLowerCase()) !== -1;
      });
    }
  },
  watch: {
    location() {
      if (this.location === null) {
        this.location = "";
      }
      this.getResults();
    },
    category() {
      if (!this.category) {
        this.category = "";
      }
      this.getResults();
    },
    fixed_price() {
      if (this.fixed_price) {
        this.fixed_price = "Fixed Price";
        this.project_type = "fixed";
        this.hourly_rate = false;
      } else {
        this.fixed_price = false;
      }

      if (!this.fixed_price && !this.hourly_rate) {
        this.project_type = "";
      }
      this.getResults();
    },
    hourly_rate() {
      if (this.hourly_rate) {
        this.hourly_rate = "Hourly Rate";
        this.project_type = "hourly";
        this.fixed_price = false;
      } else {
        this.hourly_rate = false;
      }

      if (!this.fixed_price && !this.hourly_rate) {
        this.project_type = "";
      }
      this.getResults();
    },
    project_type() {
      if (!this.hourly_rate && !this.fixed_price) {
        this.project_type = "";
      }
    },
    skills() {
      this.skills_list = "";
      for (let i in this.skills) {
        this.skills_list += this.skills[i].value;
        if (i != this.skills.length - 1) {
          this.skills_list += " ";
        }
      }
      this.getResults();
    },
    sort_by() {
      this.getResults();
    }
  },
  methods: {
    nothingToDo() {},
    getSkillsInArray(skills) {
      if (skills) {
        return skills.split(",");
      }
    },
    convertStringToInt(value) {
      return parseFloat(value);
    },
    async getRecentJob() {
      this.jobIndex = 0;
      this.hasJob = false;
      this.loadingNewJob = true;
      await this.getResults();
    },
    async getResults(page = 1) {
      this.skeletonLoading = true;
      await setTimeout(() => {
        this.axios
          .get(
            `/api/jobs/search?page=${page}
            &q=${this.query}
            &location=${encodeURIComponent(this.location)}
            &category=${encodeURIComponent(this.category)}
            &minimum=${encodeURIComponent(this.minimum)}
            &maximum=${encodeURIComponent(this.maximum)}
            &project_type=${encodeURIComponent(this.project_type)}
            &skills=${encodeURIComponent(this.skills_list)}
            &sort_by=${encodeURIComponent(this.sort_by)}`
          )
          .then(response => {
            this.jobs = response.data;
            this.skeletonLoading = false;
            this.loadingNewJob = false;
          })
          .catch(error => {
            this.showErrors(error);
          });
      }, 1000);
    }
  },
  async mounted() {
    let _this = this;
    await this.getResults();
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
    await this.axios
      .get("/api/profile/settings/category")
      .then(response => {
        this.category = response.data.data.name;
      })
      .catch(error => {
        this.showErrors(error);
      });
    await this.axios
      .get("/api/countries")
      .then(response => {
        this.countries = response.data;
      })
      .catch(error => {
        this.showErrors(error);
      });

    Echo.channel("NewJob").listen("NewJob", e => {
      if (e.category === _this.category || _this.category == "") {
        _this.jobIndex++;
        _this.hasJob = true;
      }
    });
  }
};
</script>
