<template>
    <div
        class="w-10/12 flex flex-col py-5 justify-center items-center"
        v-loading="loading"
        element-loading-text="Aguarde..."
    >
        <text-logo />
        <template v-if="step === 0">
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
                        <h2 class="text-gray-200 font-semibold mb-4">
                            Enterprise
                        </h2>
                        <p class="text-gray-300 mb-4">
                            Plano avançado para empresas.
                        </p>
                        <p class="text-green-500 font-semibold">R$ 99/mês</p>
                        <button
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
                        <h2 class="text-gray-200 font-semibold mb-4">
                            Premium
                        </h2>
                        <p class="text-gray-300 mb-4">
                            Plano completo com todos os recursos.
                        </p>
                        <p class="text-green-500 font-semibold">R$ 199/mês</p>
                        <button
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
                    <a
                        v-else-if="expired"
                        href="/login"
                        class="my-3 vstack-link"
                    >
                        Não escolher e sair
                    </a>
                    <a v-else href="/admin" class="my-3 vstack-link">
                        Continuar com meu plano atual
                    </a>
                </div>
            </div>
        </template>
        <template v-if="step == 1">
            <small class="dark:text-neutral-300">
                Preencha os dados abaixo para finalizar a assinatura. <br />
                O prazo selecionado será adicionado ao seu plano atual.
            </small>
            <div class="container mt-5 flex justify-center items-center">
                <div class="grid grid-cols-1 gap-4 w-full md:w-8/12">
                    <div
                        class="bg-gray-900 shadow-lg rounded-lg p-6 flex flex-col text-white"
                    >
                        <h2
                            class="text-gray-200 font-semibold mb-4 text-center text-lg"
                        >
                            Pagamento
                        </h2>

                        formulario de pagto aqui ...

                        <button
                            :disabled="!selectedPlan"
                            @click="submitPayment()"
                            :class="[
                                'mt-6 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full',
                                'disabled:bg-gray-300 disabled:hover:bg-gray-300 disabled:cursor-not-allowed',
                            ]"
                        >
                            Pagar
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex flex-col mt-3">
                <div class="flex justify-between">
                    <a
                        href="#"
                        class="my-3 vstack-link"
                        @click.prevent="prevStep()"
                    >
                        Voltar a seleção de plano
                    </a>
                </div>
            </div>
        </template>
    </div>
</template>
<script>
export default {
    props: ['plan', 'expired'],
    data() {
        return {
            loading: false,
            step: 0,
            paymentForm: {},
            selectedPlan: null,
        };
    },
    methods: {
        activeClass(plan) {
            return this.plan == plan ? 'border-2 border-green-500' : '';
        },
        prevStep() {
            this.step--;
        },
        submitPayment() {
            this.submit({ plan: this.selectedPlan, payment: this.paymentForm });
        },
        submit(payload) {
            this.loading = true;
            this.$http
                .post(`/choose-a-plan`, payload)
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
        selectPlan(plan) {
            this.selectedPlan = plan;
            if (plan === 'test') {
                return this.submit({ plan });
            }
            this.step++;
        },
    },
};
</script>
