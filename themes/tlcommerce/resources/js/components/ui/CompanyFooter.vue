<template>
    <footer class="company-footer c1-bg">
        <span>Designed by <a :href="designerUrl" target="_blank" rel="noopener">{{ designerName }}</a></span>
    </footer>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "CompanyFooter",

    computed: {
        ...mapState({
            siteSettings: (state) => state.siteSettings,
        }),

        designerName() {
            return this.siteSettings?.designer_name || "Platepilots.com";
        },

        designerUrl() {
            return this.siteSettings?.designer_url || "https://platepilots.com";
        },
    },
};
</script>

<style scoped>
.company-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    padding: 10px 24px;
    font-size: 12px;
    color: white;
    letter-spacing: 0.03em;
}

.company-footer a {
    color: inherit;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
}

/* .company-footer a:hover {
    color: #333;
} */
.company-footer a:hover {
    background: linear-gradient(90deg, #ff8c00, #ff4500);
    /* Adjust shades of orange here */
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    color: #ff8c00;
    /* Fallback for older browsers */
}
</style>