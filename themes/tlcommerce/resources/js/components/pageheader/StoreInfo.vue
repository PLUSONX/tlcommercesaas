<template>
  <div class="store-info p-1">
    <p class="store-info__label font-weight-bold fz-14 mb-2">
      {{ $t("Help us improve") }}
    </p>
    <div class="store-info-card">
      <router-link :to="{ name: 'customerFeedback' }" class="store-info-card__feedback-btn">
        {{ $t("Leave Feedback") }}
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: "StoreInfo",
};
</script>

<style scoped>
.store-info__label {
  margin: 0;
  color: inherit;
}

.store-info-card {
  background: #f8f9fa;
  border: 1px solid #e2e2e2;
  border-radius: 8px;
  min-height: 120px;
  padding: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.store-info-card__feedback-btn {
  display: block;
  width: 30%;
  background: transparent;
  border: 1px solid #e2e2e2;
  border-radius: 6px;
  padding: 10px 16px;
  text-align: center;
  text-decoration: none;
  color: inherit;
  font-weight: 500;
  transition: background-color 0.2s ease, border-color 0.2s ease;

  @media (max-width: 767px) {
    width: 60% !important;
  }
}

.store-info-card__feedback-btn:hover {
  background: rgba(0, 0, 0, 0.04);
  border-color: #ccc;
  color: inherit;
  text-decoration: none;
}
</style>
