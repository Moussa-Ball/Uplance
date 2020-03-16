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
                <h3>Hourly Rate</h3>
                <div class="input-with-icon">
                  <strong>${{ hourly_rate }}</strong>
                  <vue-slider
                    @drag-end="getResults()"
                    v-model="hourly_rate"
                    :value="hourly_rate"
                    :tooltip="'none'"
                    :dotSize="15"
                    :min="5"
                    :max="150"
                  />
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
            <h3 v-if="freelancers.total" class="page-title">Search Results: {{ freelancers.total }}</h3>
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
                      v-on:change="getResults"
                      v-model="sort_by"
                      class="selectpicker default"
                      data-selected-text-format="count"
                      data-size="7"
                      title="All Categories"
                    >
                      <option value="Relevance" selected>Relevance</option>
                      <option value="Newest">Newest</option>
                      <option value="Oldest">Oldest</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div
              v-if="freelancers.total === 0 && !skeletonLoading"
              class="tasks-list-container margin-top-35"
            >
              <h1 class="page-title">Sorry, we could not find a freelancer.</h1>
            </div>

            <!-- Skeleton loading -->
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

            <!-- Freelancers List Container -->
            <div class="freelancers-container freelancers-list-layout compact-list margin-top-35">
              <template v-for="(freelancer, key) in freelancers.data">
                <div class="freelancer" v-if="!skeletonLoading" :key="key">
                  <div class="freelancer-overview">
                    <div class="freelancer-overview-inner">
                      <div class="freelancer-avatar">
                        <div
                          class="verified-badge user-status"
                          :class="{'status-online': freelancer.presence_status === 'online' && freelancer.switcher_status === 'online'}"
                        ></div>
                        <a
                          v-if="freelancer.avatar[0] === 'u'"
                          target="_blank"
                          :href="`freelancers/~${freelancer.hashid}`"
                        >
                          <img class="uplance-lg-avatar" :src="'/'+freelancer.avatar" alt="avatar" />
                        </a>
                        <a v-else target="_blank" :href="`freelancers/~${freelancer.hashid}`">
                          <img class="uplance-lg-avatar" :src="freelancer.avatar" alt="avatar" />
                        </a>
                      </div>
                      <div class="freelancer-name">
                        <h4>
                          <a target="_blank" :href="`freelancers/~${freelancer.hashid}`">
                            {{ freelancer.name }}
                            <img
                              @click.prevent="() => {}"
                              class="flag"
                              :src="'/images/flags/' + freelancer.country.toLowerCase() + '.svg'"
                              alt="flag"
                              v-tippy="{ arrow : true }"
                              :content="getCountryName(freelancer.country)"
                              data-tippy-placement="top"
                            />
                          </a>
                        </h4>
                        <span
                          v-if="String(freelancer.tagline).length > 25"
                        >{{ String(freelancer.tagline).substr(0, 20) + '...' }}</span>
                        <span v-else>{{ freelancer.tagline }}</span>
                        <div class="freelancer-rating">
                          <div :data-rating="freelancer.rating" class="star-rating">
                            <star-rating
                              :style="{position: 'relative', top: 3 + 'px'}"
                              :star-size="18"
                              :read-only="true"
                              :show-rating="false"
                              :rating="freelancer.rating"
                            ></star-rating>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="freelancer-details">
                    <div class="freelancer-details-list">
                      <ul>
                        <!--<li><strong> <div :class="{'status-online': freelancer.status === 'online'}" class="user-avatar online-search"></div></strong></li>-->
                        <li>
                          Location
                          <strong>
                            <i class="icon-material-outline-location-on"></i>
                            {{ freelancer.city }}
                          </strong>
                        </li>
                        <li>
                          Rate
                          <strong>${{ freelancer.hourly_rate }} / hr</strong>
                        </li>
                        <li>
                          Job Success
                          <strong>{{ freelancer.job_success }}%</strong>
                        </li>
                      </ul>
                    </div>
                    <a
                      target="_blank"
                      :href="`freelancers/~${freelancer.hashid}`"
                      class="button button-sliding-icon ripple-effect"
                    >
                      View Profile
                      <i class="icon-material-outline-arrow-right-alt"></i>
                    </a>
                  </div>
                </div>
              </template>
            </div>
            <!-- Freelancers Container / End -->
            <div class="clearfix"></div>
            <div v-if="freelancers" class="row">
              <div class="col-md-12">
                <div class="pagination-container margin-top-60 margin-bottom-60">
                  <nav class="pagination">
                    <pagination :limit="5" :data="freelancers" @pagination-change-page="getResults"></pagination>
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
      freelancers: {},
      location: "",
      category: "",
      options: [],
      countries: [],
      hourly_rate: 150,
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: false
      },
      query: "",
      skill: "",
      skills: [],
      skeletonLoading: true,
      autocompleteSkills: [],
      sort_by: "Relevance"
    };
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
    async getResults(page = 1) {
      this.skeletonLoading = true;
      await setTimeout(() => {
        this.axios
          .get(
            `/api/freelancers/search?page=${page}
            &q=${this.query}
            &location=${encodeURIComponent(this.location)}
            &category=${encodeURIComponent(this.category)}
            &hourly_rate=${encodeURIComponent(this.hourly_rate)}
            &skills=${encodeURIComponent(this.skills_list)}
            &sort_by=${encodeURIComponent(this.sort_by)}`
          )
          .then(response => {
            this.freelancers = response.data;
            this.skeletonLoading = false;
          })
          .catch(error => {
            this.showErrors(error);
          });
      }, 1000);
    }
  },
  async mounted() {
    let _this = this;
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

    await _this.getResults();

    Echo.join("uplance")
      .listen("UserOnline", function(user) {
        for (let key in _this.freelancers.data) {
          if (_this.freelancers.data[key].hashid == user.hashid) {
            _this.freelancers.data[key].presence_status = user.presence_status;
            _this.freelancers.data[key].switcher_status = user.switcher_status;
          }
        }
      })
      .listen("UserOffline", function(user) {
        for (let key in _this.freelancers.data) {
          if (_this.freelancers.data[key].hashid == user.hashid) {
            _this.freelancers.data[key].presence_status = user.presence_status;
            _this.freelancers.data[key].switcher_status = user.switcher_status;
          }
        }
      });
  }
};
</script>

<style>
.freelancer-details {
  min-width: 50% !important;
}

.freelancer-details-list li {
  margin-left: 20px;
  margin-right: 20px;
}

.verified-badge.user-status {
  background: #c0c0c0;
  padding: 9px;
  border-bottom: 1px solid #e6e6e6;
  position: absolute;
  bottom: 8px !important;
  right: 0 !important;
  border: 3px solid #ffffff;
}

.verified-badge.user-status.status-online {
  background: #38b653;
}

.verified-badge.user-status:before {
  content: "";
  padding: -1px !important;
}
</style>