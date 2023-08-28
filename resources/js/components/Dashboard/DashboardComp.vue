<template>
    <div class="flex flex-col md:flex-row gap-3 pt-3">
        <CardQtyDemands />
        <CardQtyProjects />
        <CardQtyCustomers />
        <CardQtyPartners />
        <!-- <custom-option-selector
            label="Produtos"
            description="Selecione o produto que deseja utilizar neste checkout"
            ref="productSelector"
            model="App\Http\Models\Permission"
            :filters="[
                {
                    label: 'Produtos Físicos',
                    index: 'type',
                    group: 'Tipo',
                    disabled: false,
                    value: 'FI',
                },
                {
                    label: 'Produtos Digitais',
                    index: 'type',
                    group: 'Tipo',
                    disabled: false,
                    value: 'DI',
                },
                {
                    label: 'Produtos Agrupados',
                    index: 'type',
                    group: 'Tipo',
                    disabled: false,
                    value: 'GR',
                },
                {
                    label: 'Plano Assinatura',
                    index: 'type',
                    value: 'AS',
                    group: 'Tipo',
                    disabled: true,
                },
            ]"
        >
            <template v-slot:option="{ item }">
                {{ item.original.name }}
            </template>
        </custom-option-selector>
        <button @click="openDialog">abrir</button> -->
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
