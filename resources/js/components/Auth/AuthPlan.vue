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
                O prazo selecionado será adicionado ao seu plano atual.
            </small>
            <div class="container mt-5 flex justify-center items-center">
                <div class="grid grid-cols-1 gap-4 w-full md:w-8/12">
                    <div
                        class="bg-gray-900 shadow-lg rounded-lg p-6 flex flex-col text-white"
                    >
                        <p class="text-xl font-medium">Detalhes do pagamento</p>
                        <p class="text-gray-400">
                            Preencha os dados abaixo para finalizar a assinatura
                        </p>
                        <div class="">
                            <label
                                for="card-holder"
                                class="mt-4 mb-2 block text-sm font-medium"
                            >
                                Titular do cartão
                            </label>
                            <div class="relative">
                                <input
                                    v-model="paymentForm.cardName"
                                    :class="[
                                        'w-full rounded-md border border-gray-200 px-4 py-3 pl-11',
                                        'text-sm uppercase shadow-sm outline-none focus:z-10 focus:border-blue-500',
                                        'focus:ring-blue-500 text-gray-900 uppercase',
                                    ]"
                                    placeholder="Nome escrito no cartão"
                                />
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 inline-flex items-center px-3"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            :d="[
                                                'M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25',
                                                '0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25',
                                                '2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875',
                                                '1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0',
                                                '01-3.168-.789 3.376 3.376 0 016.338 0z',
                                            ]"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <label
                                for="card-no"
                                class="mt-4 mb-2 block text-sm font-medium"
                            >
                                Dados do cartão
                            </label>
                            <div class="flex gap-3">
                                <div class="relative w-7/12 flex-shrink-0">
                                    <input
                                        v-model="paymentForm.cardNumber"
                                        v-mask="['#### #### #### ####']"
                                        :class="[
                                            'w-full rounded-md border border-gray-200 px-2 py-3 pl-11 text-sm shadow-sm',
                                            'outline-none focus:z-10 focus:border-blue-500 focus:ring-blue-500 text-gray-900',
                                        ]"
                                        placeholder="xxxx-xxxx-xxxx-xxxx"
                                    />
                                    <div
                                        class="pointer-events-none absolute inset-y-0 left-0 inline-flex items-center px-3"
                                    >
                                        <svg
                                            class="h-4 w-4 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                :d="[
                                                    'M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0',
                                                    '0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1z',
                                                ]"
                                            />
                                            <path
                                                :d="[
                                                    'M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0',
                                                    '0-2-2H2zm13 2v5H1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm-1 9H2a1 1 0 0',
                                                    '1-1-1v-1h14v1a1 1 0 0 1-1 1z',
                                                ]"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <input
                                    v-model="paymentForm.cardExpiry"
                                    v-mask="['##/##']"
                                    :class="[
                                        'w-full rounded-md border border-gray-200 px-2 py-3 text-sm shadow-sm outline-none',
                                        'focus:z-10 focus:border-blue-500 focus:ring-blue-500 text-gray-900',
                                    ]"
                                    placeholder="MM/YY"
                                />
                                <input
                                    v-model="paymentForm.cardCvc"
                                    v-mask="['###']"
                                    :class="[
                                        'w-1/6 flex-shrink-0 rounded-md border border-gray-200 px-2 py-3 text-sm shadow-sm',
                                        'outline-none focus:z-10 focus:border-blue-500 focus:ring-blue-500 text-gray-900',
                                    ]"
                                    placeholder="CVC"
                                />
                            </div>
                            <label
                                for="card-no"
                                class="mt-4 mb-2 block text-sm font-medium"
                            >
                                Meses de contratação
                            </label>
                            <div class="flex gap-3">
                                <div class="relative w-6/12 flex-shrink-0">
                                    <select
                                        id="card-no"
                                        v-model="paymentForm.months"
                                        :class="[
                                            'w-full rounded-md border border-gray-200 px-4 py-3 text-sm shadow-sm',
                                            'outline-none focus:z-10 focus:border-blue-500 focus:ring-blue-500 text-gray-900',
                                        ]"
                                    >
                                        <option
                                            v-for="i in 12"
                                            :key="i"
                                            :value="i"
                                        >
                                            {{
                                                `${i} ${
                                                    i > 1 ? 'meses' : 'mês'
                                                }`
                                            }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="mt-6 border-t border-b py-2">
                                <div class="flex items-center justify-between">
                                    <p
                                        class="text-sm font-medium text-gray-300"
                                    >
                                        Subtotal
                                    </p>
                                    <p class="font-semibold text-gray-200">
                                        R$ 399.00
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-300">
                                    Total
                                </p>
                                <p class="text-2xl font-semibold text-gray-200">
                                    R$ 408.00
                                </p>
                            </div>
                        </div>
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
            paymentForm: {
                months: 12,
                holdName: '',
                cardNumber: '',
                cardExpiry: '',
                cardCvc: '',
            },
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
