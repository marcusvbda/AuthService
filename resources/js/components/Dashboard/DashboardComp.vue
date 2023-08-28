<template>
    <div class="flex flex-col md:flex-row gap-3 pt-3">
        <CardQtyDemands />
        <CardQtyProjects />
        <CardQtyCustomers />
        <CardQtyPartners />
    </div>
</template>
<script>
import CardQtyCustomers from './partials/-card-qty-customers.vue';
import CardQtyProjects from './partials/-card-qty-projects.vue';
import CardQtyPartners from './partials/-card-qty-partners.vue';
import CardQtyDemands from './partials/-card-qty-demands.vue';
export default {
    components: {
        CardQtyCustomers,
        CardQtyProjects,
        CardQtyPartners,
        CardQtyDemands,
    },
    methods: {
        openDialog() {
            this.$refs.productSelector.open();
        },
    },
};
</script>
