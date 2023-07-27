<template>
    <div
        class="w-10/12 flex flex-col py-5 justify-center items-center"
        v-loading="loading"
        element-loading-text="Aguarde..."
    >
        <text-logo />
        <small class="dark:text-neutral-300">
            Selecione o plano que deseja utilizar do sistema
        </small>
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div
                    :class="`bg-gray-900 shadow-lg rounded-lg p-6 ${activeClass(
                        'basic'
                    )}`"
                >
                    <h2 class="text-gray-200 font-semibold mb-4">Basic</h2>
                    <p class="text-gray-300 mb-4">
                        Plano básico com recursos limitados.
                    </p>
                    <p class="text-green-500 font-semibold">R$ 20/mês</p>
                    <button
                        :disabled="plan == 'basic'"
                        @click="selectPlan('basic')"
                        :class="[
                            'mt-6 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full',
                            'disabled:bg-gray-300 disabled:hover:bg-gray-300 disabled:cursor-not-allowed',
                        ]"
                    >
                        Selecionar
                    </button>
                </div>
                <div
                    :class="`bg-gray-900 shadow-lg rounded-lg p-6 ${activeClass(
                        'enterprise'
                    )}`"
                >
                    <h2 class="text-gray-200 font-semibold mb-4">Enterprise</h2>
                    <p class="text-gray-300 mb-4">
                        Plano avançado para empresas.
                    </p>
                    <p class="text-green-500 font-semibold">R$ 99/mês</p>
                    <button
                        :disabled="plan == 'enterprise'"
                        @click="selectPlan('enterprise')"
                        :class="[
                            'mt-6 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full',
                            'disabled:bg-gray-300 disabled:hover:bg-gray-300 disabled:cursor-not-allowed',
                        ]"
                    >
                        Selecionar
                    </button>
                </div>
                <div
                    :class="`bg-gray-900 shadow-lg rounded-lg p-6 ${activeClass(
                        'premium'
                    )}`"
                >
                    <h2 class="text-gray-200 font-semibold mb-4">Premium</h2>
                    <p class="text-gray-300 mb-4">
                        Plano completo com todos os recursos.
                    </p>
                    <p class="text-green-500 font-semibold">R$ 199/mês</p>
                    <button
                        :disabled="plan == 'premium'"
                        @click="selectPlan('premium')"
                        :class="[
                            'mt-6 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full',
                            'disabled:bg-gray-300 disabled:hover:bg-gray-300 disabled:cursor-not-allowed',
                        ]"
                    >
                        Selecionar
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-col mt-3">
            <div class="flex justify-between">
                <a
                    v-if="!plan"
                    href="#"
                    class="my-3 vstack-link"
                    @click="selectPlan('test')"
                >
                    Testar por 15 dias gratuitamente
                </a>
                <a v-else-if="expired" href="/login" class="my-3 vstack-link">
                    Não escolher e sair
                </a>
                <a v-else href="/admin" class="my-3 vstack-link">
                    Continuar com meu plano atual
                </a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['plan', 'expired'],
    data() {
        return {
            loading: false,
        };
    },
    methods: {
        activeClass(plan) {
            return this.plan == plan ? 'border-2 border-green-500' : '';
        },
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
