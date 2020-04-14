<template>
  <div class="row">
    <!-- BEGIN ACCOUNT INFORMATION -->
    <div class="col-xl-12">
      <div class="dashboard-box margin-top-0">
        <!-- Headline -->
        <div class="headline">
          <h3>
            <i class="icon-material-outline-account-circle"></i> My Account
          </h3>
        </div>

        <div v-show="!loading" class="content with-padding">
          <div class="row">
            <div class="col-auto">
              <div class="avatar-wrapper" data-tippy-placement="bottom" title="Change Avatar">
                <img class="profile-pic" :src="user.avatar" alt="avatar" />
                <div class="upload-button"></div>
                <input v-on:change="updateAvatar" class="file-upload" type="file" accept="image/*" />
              </div>
              <span
                v-if="avatarUploading"
                class="margin-bottom-30"
                style="position: relative; left: 55px;"
              >
                <self-building-square-spinner
                  :animation-duration="5000"
                  :size="40"
                  color="#2a41e8"
                />
              </span>
            </div>

            <div class="col">
              <div class="row">
                <div class="col-xl-6">
                  <div class="submit-field">
                    <h5>First Name</h5>
                    <input required v-model="user.first_name" type="text" class="with-border" />
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="submit-field">
                    <h5>Last Name</h5>
                    <input required v-model="user.last_name" type="text" class="with-border" />
                  </div>
                </div>

                <div v-if="!user.account_type" class="col-xl-6">
                  <div class="submit-field">
                    <h5>
                      Account Type
                      <strong>(Unique choice)</strong>
                    </h5>
                    <div class="account-type">
                      <div>
                        <input
                          @change="updateAccount"
                          v-model="user.account_type"
                          type="radio"
                          value="freelancer"
                          id="freelancer-radio"
                          class="account-type-radio"
                        />
                        <label for="freelancer-radio" class="ripple-effect-dark">
                          <i class="icon-material-outline-account-circle"></i> Freelancer
                        </label>
                      </div>

                      <div>
                        <input
                          @change="updateAccount"
                          v-model="user.account_type"
                          type="radio"
                          value="client"
                          id="employer-radio"
                          class="account-type-radio"
                        />
                        <label for="employer-radio" class="ripple-effect-dark">
                          <i class="icon-material-outline-business-center"></i> Client
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xl-12">
                  <div class="submit-field">
                    <h5>Email</h5>
                    <input required v-model="user.email" type="text" class="with-border" />
                  </div>
                </div>

                <div class="col-xl-12">
                  <button
                    v-if="!accountUpdating"
                    @click.prevent.stop="updateAccount"
                    type="button"
                    class="button ripple-effect big margin-bottom-30"
                  >Save changes</button>
                  <half-circle-spinner
                    style="float: left; margin: 20px 50px;"
                    v-if="accountUpdating"
                    :animation-duration="800"
                    :size="60"
                    color="#2a41e8"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- BEGIN ACCOUNT INFORMATION -->

    <!-- BEGIN PROFILE INFORMATION -->
    <div v-if="user.current_account" class="col-xl-12">
      <div class="dashboard-box">
        <div class="headline" style="display: flex; justify-content: space-between;">
          <h3>
            <i class="icon-material-outline-face"></i> My Profile
          </h3>
          <strong>This information below will be used for verification of your account, your payments or withdrawal.</strong>
        </div>
        <div v-if="!loading" class="content">
          <ul class="fields-ul">
            <li>
              <div class="row">
                <div v-if="user.current_account == 'freelancer'" class="col-xl-4">
                  <div class="submit-field">
                    <div class="bidding-widget">
                      <!-- Headline -->
                      <span class="bidding-detail">
                        Set your
                        <strong>minimal hourly rate</strong>
                      </span>

                      <!-- Slider -->
                      <div class="bidding- My Accountvalue margin-bottom-10">
                        <strong>
                          <h2>${{ user.hourly_rate }}</h2>
                        </strong>
                      </div>
                      <vue-slider
                        v-model="user.hourly_rate"
                        :value="user.hourly_rate"
                        :tooltip="'none'"
                        :dotSize="15"
                        :min="5"
                        :max="150"
                      />
                    </div>
                  </div>
                </div>
                <div
                  class="col-xl-8"
                  :class="{'col-xl-12': user.current_account == 'client' || !user.account_type}"
                >
                  <div class="submit-field">
                    <h5>Tagline</h5>
                    <input
                      v-model="user.tagline"
                      type="text"
                      class="with-border"
                      placeholder="iOS Expert + Node Dev"
                    />
                  </div>
                </div>
              </div>
            </li>
            <li>
              <div class="row">
                <div class="col-xl-6">
                  <div class="submit-field">
                    <h5>City</h5>
                    <input v-model="user.city" type="text" class="with-border" />
                  </div>
                </div>
                <div class="col-xl-6">
                  <div class="submit-field">
                    <h5>Address</h5>
                    <input v-model="user.address" type="text" class="with-border" />
                  </div>
                </div>
                <div class="col-xl-6">
                  <div class="submit-field">
                    <h5>Postal Code</h5>
                    <input v-model="user.postal_code" type="text" class="with-border" />
                  </div>
                </div>
                <div class="col-xl-6">
                  <div class="submit-field">
                    <h5>Mobile Phone</h5>
                    <vue-tel-input
                      v-model="user.mobile_phone"
                      :ignoredCountries="['il']"
                      :preferredCountries="['us', 'gb', 'ua', 'ca']"
                    ></vue-tel-input>
                  </div>
                </div>
                <div class="col-xl-12">
                  <div class="submit-field">
                    <h5>Country</h5>
                    <country-select
                      :class="'select-country-region'"
                      v-model="user.country"
                      :blackList="['IL']"
                      :country="user.country"
                    />
                  </div>
                </div>
                <div v-if="user.current_account == 'freelancer'" class="col-xl-12">
                  <div class="submit-field">
                    <h5>Category</h5>
                    <multiselect
                      v-model="category"
                      :options="options"
                      :searchable="false"
                      :close-on-select="true"
                      :show-labels="false"
                      placeholder="Choose category"
                    ></multiselect>
                  </div>
                </div>
                <div v-if="user.current_account == 'freelancer'" class="col-xl-12">
                  <div class="submit-field">
                    <h5>
                      Skills
                      <i
                        class="help-icon"
                        data-tippy-placement="right"
                        title="Add up to 10 skills"
                      ></i>
                    </h5>
                    <tags-input
                      element-id="tags"
                      v-model="skills"
                      placeholder="e.g. PHP or CSS3"
                      :limit="10"
                      :existing-tags="autocompleteSkills"
                      :typeahead="true"
                    ></tags-input>
                  </div>
                </div>

                <div v-if="user.current_account == 'freelancer'" class="col-xl-12">
                  <div class="submit-field">
                    <h5>Introduce Yourself</h5>
                    <textarea v-model="user.presentation" cols="30" rows="5" class="with-border"></textarea>
                  </div>
                </div>
                <div class="col-xl-12">
                  <button
                    v-if="!profileUpdating && user.account_type"
                    @click="updateProfile"
                    type="button"
                    class="button ripple-effect big margin-bottom-30"
                  >Save changes</button>
                  <half-circle-spinner
                    style="float: left; margin: 20px 50px;"
                    v-if="profileUpdating"
                    :animation-duration="800"
                    :size="60"
                    color="#2a41e8"
                  />
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- END PROFILE INFORMATION -->

    <!-- BEGIN CHANGE PASSWORD -->
    <div class="col-xl-12">
      <div id="test1" class="dashboard-box">
        <div class="headline">
          <h3>
            <i class="icon-material-outline-lock"></i> Password & Security
          </h3>
        </div>

        <div v-if="!loading" class="content with-padding">
          <div class="row">
            <div v-if="user.password" class="col-xl-4">
              <div class="submit-field">
                <h5>Current Password</h5>
                <input v-model="password.current_password" type="password" class="with-border" />
              </div>
            </div>

            <div :class="{'col-xl-4': user.password, 'col-xl-6': !user.password}">
              <div class="submit-field">
                <h5>Password</h5>
                <input v-model="password.password" required type="password" class="with-border" />
              </div>
            </div>

            <div :class="{'col-xl-4': user.password, 'col-xl-6': !user.password}">
              <div class="submit-field">
                <h5>Password Confirmation</h5>
                <input
                  v-model="password.password_confirmation"
                  required
                  type="password"
                  class="with-border"
                />
              </div>
            </div>

            <div class="col-xl-12">
              <button
                v-if="!passwordUpdating"
                @click.prevent="updatePassword"
                type="button"
                class="button ripple-effect big"
              >Change password</button>
              <half-circle-spinner
                style="float: left; margin: 20px 50px;"
                v-if="passwordUpdating"
                :animation-duration="800"
                :size="60"
                color="#2a41e8"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-12 margin-top-30 bg-red">
      <button
        @click.prevent="removeAccount"
        type="button"
        class="button ripple-effect big"
        style="float: right; background: red;"
      >Remove account</button>
    </div>
    <!-- END CHANGE PASSWORD -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {},
      options: [],
      loading: true,
      avatarUploading: false,
      accountUpdating: false,
      profileUpdating: false,
      passwordUpdating: false,
      skill: "",
      skills: [],
      autocompleteSkills: [],
      category: "",
      skills_list: "",
      password: {
        password: "",
        current_password: "",
        password_confirmation: ""
      }
    };
  },
  computed: {
    filteredItems() {
      return this.autocompleteSkills.filter(i => {
        return i.text.toLowerCase().indexOf(this.skill.toLowerCase()) !== -1;
      });
    }
  },
  watch: {
    "user.skills": function() {
      if (this.user.skills.length) {
        let skills = this.user.skills;
        skills = skills.split(",");
        for (let i in skills) {
          this.skills.push({ key: skills[i], value: skills[i] });
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
    async removeAccount() {
      let deleted = await confirm(
        "You are sure you want to close your account ?"
      );
      if (deleted) {
        deleted = await confirm(
          "Are you sure that you will lose all your statistics, your data, your success, your reviews and others?"
        );
      }
      if (deleted) {
        await this.axios
          .delete("/api/profile/settings/remove-account")
          .then(response => {
            if (response.status == 200) {
              window.location.href = "/";
            }
          })
          .catch(error => {
            this.showErrors(error);
          });
      }
    },
    /**
     * Allows to update avatar.
     */
    async updateAvatar(e) {
      let formData = new FormData();
      this.user.avatar = e.target.files[0];
      formData.append("avatar", this.user.avatar);

      const config = {
        headers: { "content-type": "multipart/form-data" }
      };

      this.avatarUploading = true;
      await this.axios
        .post("/api/profile/settings/update-avatar", formData, config)
        .then(response => {
          this.showNotification(response.data, "success", true, 5000);
        })
        .catch(error => {
          this.showErrors(error);
          this.showNotification(
            "The size of the image must be also at least 100px or 500px maximum.",
            "error",
            true,
            5000
          );
        });
      this.avatarUploading = false;
    },
    /**
     * Allows to update account.
     */
    async updateAccount() {
      this.accountUpdating = true;
      await this.axios
        .put("/api/profile/settings/update-account", this.user)
        .then(response => {
          if (!this.user.current_account) {
            this.getUser();
          }
          this.showNotification(response.data, "success", true, 5000);
        })
        .catch(error => {
          this.showErrors(error);
        });
      this.accountUpdating = false;
    },
    /**
     * Allows to update profile.
     */
    async updateProfile() {
      this.profileUpdating = true;
      await this.axios
        .put("/api/profile/settings/update-profile", {
          hourly_rate: this.user.hourly_rate,
          tagline: this.user.tagline,
          city: this.user.city.split(",")[0],
          address: this.user.address,
          postal_code: this.user.postal_code,
          mobile_phone: this.user.mobile_phone,
          country: this.user.country,
          category: this.category,
          skills: this.skills_list,
          presentation: this.user.presentation
        })
        .then(response => {
          this.showNotification(response.data, "success", true, 5000);
        })
        .catch(error => {
          this.showErrors(error);
        });
      this.profileUpdating = false;
    },
    /**
     * Allows to change password.
     */
    async updatePassword() {
      this.passwordUpdating = true;
      await this.axios
        .put("/api/profile/settings/update-password", this.password)
        .then(response => {
          this.password.current_password = "";
          this.password.password = "";
          this.password.password_confirmation = "";
          this.showNotification(response.data, "success", true, 5000);
          this.getUser();
        })
        .catch(error => {
          this.showErrors(error);
        });
      this.passwordUpdating = false;
    },
    /**
     * Allows to get the current user.
     */
    async getUser() {
      this.axios
        .get("/api/user")
        .then(response => {
          this.user = response.data.data;
        })
        .catch(error => {
          this.showErrors(error);
        });
    }
  },
  /**
   * Get the logged user if the component is mounted.
   */
  async mounted() {
    let _this = this;
    this.loading = true;
    await this.getUser();
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

    this.loading = false;
  }
};
</script>

<style lang="scss">
select.select-country-region {
  height: 55px;
  border: 1px solid #e0e0e0 !important;
  box-shadow: 0 1px 4px 0px rgba(0, 0, 0, 0.05) !important;
}

.vue-slider-tooltip {
  display: block;
  font-size: 14px;
  white-space: nowrap;
  padding: 2px 5px;
  min-width: 20px;
  text-align: center;
  color: #fff;
  border-radius: 5px;
  border: 1px solid #2a41e8 !important;
  background-color: #2a41e8 !important;
}

.vue-slider-process {
  position: absolute;
  border-radius: 15px;
  background-color: #2a41e8 !important;
  transition: all 0s;
  z-index: 1;
}

.vue-tel-input {
  height: 50px;
  border: 1px solid #e0e0e0 !important;
  box-shadow: 0 1px 4px 0px rgba(0, 0, 0, 0.05) !important;

  .dropdown {
    border: none;
    outline: none;
  }

  input {
    border: none;
    border-radius: 0 2px 2px 0;
    width: 100%;
    outline: none;
    padding-left: 6px;
    box-shadow: none !important;
  }
}

.vue-slider-dot-handle {
  cursor: pointer;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #fff;
  border: 2px solid #2a41e8;
  box-sizing: border-box;
  transition: box-shadow 0.3s, border-color 0.3s;
}

.vue-slider:hover .vue-slider-dot-handle:hover {
  border-color: #2a41e8;
}

.vue-slider-rail:hover {
  .vue-slider:hover .vue-slider-dot-handle:hover {
    border-color: #2a41e8;
  }
}

.vue-tags-input[data-v-61d92e31] {
  max-width: 100%;
  position: relative;
  background-color: #fff;
}

.ti-input {
  border-radius: 4px !important;
  border: 1px solid #e0e0e0 !important;
  box-shadow: 0 1px 4px 0px rgba(0, 0, 0, 0.05) !important;
}

.ti-new-tag-input {
  box-shadow: none !important;
}

.ti-tag[data-v-61d92e31] {
  background-color: #2a41e8;
  color: #fff;
  border-radius: 2px;
  display: flex;
  padding: 3px 5px;
  margin: 2px;
  font-size: 0.85em;
}

.ti-tags[data-v-61d92e31] {
  display: flex;
  flex-wrap: wrap;
  width: 100%;
  line-height: 1em;
}

.ti-tag[data-v-61d92e31] {
  margin: 15px 2px;
}

.ti-autocomplete[data-v-61d92e31] {
  background-color: #fff;
  border: 1px solid #e0e0e0 !important;
  width: 100%;
  position: relative;
  bottom: 20px;
  z-index: 20;
}

.ti-item.ti-valid.ti-selected-item {
  background-color: #2a41e8 !important;
  color: #fff !important;
}
</style>
