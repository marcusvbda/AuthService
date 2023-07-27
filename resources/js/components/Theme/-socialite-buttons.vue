<template>
    <div class="social-auth-buttons" v-if="enabled_providers.length">
        <a
            v-for="(prov, i) in enabled_providers"
            :href="`/socialite/login/${prov}`"
            :class="`social-auth-button ${prov}`"
            :key="i"
        >
            <i :class="`fab fa-${prov}`" />
            Continue com {{ prov }}
        </a>
    </div>
</template>
<script>
export default {
    data() {
        return {
            enabled_providers: laravel.config.enabled_providers,
        };
    },
};
</script>
