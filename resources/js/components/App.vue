<template>
  <main>
    <fade-transition :duration="250" mode="out-in" origin="center">
      <router-view />
    </fade-transition>
  </main>
</template>

<script>
    import { FadeTransition } from "vue2-transitions";
    export default { components: { FadeTransition } };
</script>
