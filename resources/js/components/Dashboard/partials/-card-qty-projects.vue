<template>
    <div
        class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full md:w-3/12 cursor-pointer"
        @click="$goToPage('/admin/projects')"
    >
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
            Projetos
        </h2>
        <p
            class="text-4xl font-bold text-gray-800 dark:text-white mb-2"
            v-html="
                card.isLoading
                    ? `<i class='fas fa-spinner 
                    fa-spin opacity-30'/>`
                    : card.qty
            "
        />
    </div>
</template>
<script>
export default {
    data() {
        return {
            test: 1,
            card: {
                isLoading: true,
                qty: 0,
            },
        };
    },
    created() {
        this.getContent();
    },
    methods: {
        getContent() {
            this.$set(this.card, 'isLoading', true);
            this.$http.get('/admin/get-data/projectsQty').then(({ data }) => {
                this.$set(this.card, 'qty', data);
                this.$set(this.card, 'isLoading', false);
            });
        },
    },
};
</script>
