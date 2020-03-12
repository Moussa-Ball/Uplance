/**
 * You can register global methods & use them as methods in the main Vue instance.
 */

/**
 * Allows to display an error from laravel validation.
 */
const showError = function(error) {
    console.log("It works!");
};

/**
 * Allows to display errors from laravel validation.
 * @param {*} errors Contains data errors as an object.
 */
const showErrors = function(errors) {
    if (errors.message && !errors.response) {
        return new Noty({
            text: "<strong>" + errors.message + "</strong>",
            type: "error",
            theme: "metroui",
            progressBar: true,
            timeout: 5000
        }).show();
    }

    let data = errors.response.data;
    for (let key in data.errors) {
        new Noty({
            text: "<strong>" + data.errors[key][0] + "</strong>",
            type: "error",
            theme: "metroui",
            progressBar: true,
            timeout: 5000
        }).show();
    }
};

const showNotification = function(text, type, progress, timeout) {
    new Noty({
        text: `<strong>${text}</strong>`,
        type: type,
        theme: "metroui",
        progressBar: progress,
        timeout: timeout
    }).show();
};

const { getName } = require("country-list");

/**
 * Allows to show a modal.
 * @param {*} name The modal name.
 */
const showModal = function(name) {
    this.$modal.show(name);
};

/**
 * Allows to close a modal.
 * @param {*} name The modal name.
 */
const closeModal = function(name) {
    this.$modal.hide(name);
};

const GlobalMethods = {
    install(Vue) {
        Vue.prototype.showModal = showModal;
        Vue.prototype.closeModal = closeModal;
        Vue.prototype.showErrors = showErrors;
        Vue.prototype.getCountryName = getName;
        Vue.prototype.showNotification = showNotification;
    }
};

export default GlobalMethods;
