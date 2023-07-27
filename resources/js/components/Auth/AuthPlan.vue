<template>
    <div
        class="w-10/12 flex flex-col py-5 justify-center items-center"
        v-loading="loading"
        element-loading-text="Verificando credenciais"
    >
        <text-logo />
        <small class="dark:text-neutral-300">
            Selecione o plano que deseja utilizar do sistema
        </small>
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Free</h2>
                    <p class="text-gray-600 mb-4">
                        Plano básico com recursos limitados.
                    </p>
                    <p class="text-green-500 text-xl font-semibold">Grátis</p>
                    <button
                        @click="selectPlan('free')"
                        class="mt-6 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full"
                    >
                        Selecionar
                    </button>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Enterprise</h2>
                    <p class="text-gray-600 mb-4">
                        Plano avançado para empresas.
                    </p>
                    <p class="text-green-500 text-xl font-semibold">$99/mês</p>
                    <button
                        @click="selectPlan('enterprise')"
                        class="mt-6 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full"
                    >
                        Selecionar
                    </button>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Premium</h2>
                    <p class="text-gray-600 mb-4">
                        Plano completo com todos os recursos.
                    </p>
                    <p class="text-green-500 text-xl font-semibold">$199/mês</p>
                    <button
                        @click="selectPlan('premium')"
                        class="mt-6 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full"
                    >
                        Selecionar
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-col mt-3">
            <div class="flex justify-between">
                <a href="/login" class="my-3 text-sm vstack-link">
                    Não escolher nenhum
                </a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
        };
    },
    methods: {
        selectPlan(plan) {
            this.loading = true;
            this.$http
                .post(`/choose-a-plan`, { plan })
                .then(({ data }) => {
                    if (!data.success) {
                        this.$message(data.message);
                        return (this.loading = false);
                    } else {
                        if (data.success) {
                            return (window.location.href = data.route);
                        }
                        this.$message(data.message);
                    }
                })
                .catch((er) => {
                    this.loading = false;
                    this.errors = er.response.data.errors;
                    this.$validationErrorMessage(er);
                });
        },
    },
};
</script>
