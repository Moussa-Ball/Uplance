// tests/js/components/ExampleComponent.spec.js
import expect from "expect";
import { mount } from "@vue/test-utils";
import App from "../../../resources/js/components/App.vue";

describe("App.vue", () => {
    it("test if App component is mounted correctly.", () => {
        const wrapper = mount(App);
        //expect(wrapper.html()).toContain("Example Component");
    });
});
