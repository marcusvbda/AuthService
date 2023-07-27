<template>
    <div
        class="w-10/12 flex flex-col py-5"
        v-loading="loading"
        element-loading-text="Verificando credenciais"
    >
        <text-logo />
        <b class="mt-4 dark:text-neutral-200">Login</b>
        <small class="dark:text-neutral-300">
            Bem Vindo de volta! Efetue o login para continuar
        </small>
        <form v-on:submit.prevent="checkUser" class="vstack-form">
            <div class="flex flex-col mt-8">
                <label class="form-label">Email</label>
                <input
                    class="form-input"
                    v-model="form.email"
                    type="email"
                    required
                />
            </div>
            <div class="flex flex-col mt-2">
                <label class="form-label">Senha</label>
                <input
                    class="form-input"
                    v-model="form.password"
                    type="password"
                    required
                />
            </div>
            <div class="flex flex-col mt-3">
                <button class="vstack-btn primary">Efetuar Login</button>
                <div class="flex justify-between">
                    <a href="/register" class="my-3 text-sm vstack-link">
                        Ainda não tenho uma conta
                    </a>
                    <a
                        href="/forgot-my-password"
                        class="my-3 text-sm vstack-link"
                    >
                        Esqueceu a senha ?
                    </a>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            form: {
                email: '',
                password: '',
            },
        };
    },
    methods: {
        checkUser() {
            this.loading = true;
            this.$http
                .post(`/login`, this.form)
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
