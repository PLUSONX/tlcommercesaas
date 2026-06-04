<template>
  <feedback-form />
</template>

<script>
import FeedbackForm from "@/components/pageheader/FeedbackForm.vue";

export default {
  name: "CustomerFeedbackPage",
  components: {
    FeedbackForm,
  },
  mounted() {
    document.title = this.$t("Leave Feedback");
  },
};
</script>
