<template>
    <div
        class="w-10/12 flex flex-col py-5"
        v-loading="loading"
        element-loading-text="Verificando credenciais"
    >
        <text-logo />
        <b class="mt-4 dark:text-neutral-200">Esqueceu senha ?</b>
        <small class="dark:text-neutral-300">
            Verifique seu email para renovar sua senha.
        </small>
        <form v-on:submit.prevent="submit" class="vstack-form">
            <div class="flex flex-col mt-8">
                <label class="form-label">Email</label>
                <input
                    class="form-input"
                    v-model="form.email"
                    type="email"
                    required
                />
            </div>
            <div class="flex flex-col mt-3">
                <button class="vstack-btn primary">
                    Solicitar renovação de senha
                </button>
                <div class="flex justify-between">
                    <a href="/login" class="my-3 text-sm vstack-link">
                        Efetuar login
                    </a>
                    <a href="/register" class="my-3 text-sm vstack-link">
                        ainda não tenho uma conta
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
            },
        };
    },
    methods: {
        submit() {
            this.loading = true;
            this.$http
                .post(`/forgot-my-password`, this.form)
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
