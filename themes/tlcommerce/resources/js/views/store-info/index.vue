<template>
  <store-info />
</template>

<script>
import StoreInfo from "@/components/pageheader/StoreInfo.vue";

export default {
  name: "StoreInfoPage",
  components: {
    StoreInfo,
  },
  mounted() {
    document.title = this.$t("Store Info");
  },
};
</script>
