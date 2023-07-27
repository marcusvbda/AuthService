<template>
    <div
        class="w-10/12 flex flex-col py-5"
        v-loading="loading"
        element-loading-text="Aguarde..."
    >
        <text-logo />
        <b class="mt-4 dark:text-neutral-200">Renovação de senha</b>
        <small class="dark:text-neutral-300">
            Preencha os campos e corretamente para renovar suas credenciais.
        </small>
        <form v-on:submit.prevent="submit" class="vstack-form">
            <div class="flex flex-col mt-8">
                <label class="form-label">Senha</label>
                <input
                    class="form-input"
                    v-model="form.password"
                    type="password"
                    required
                />
            </div>
            <div class="flex flex-col mt-2">
                <label class="form-label">Confirme a senha</label>
                <input
                    class="form-input"
                    v-model="form.confirm_password"
                    type="password"
                    required
                />
            </div>
            <div class="flex flex-col mt-3">
                <button class="vstack-btn primary">Renovar senha</button>
                <div class="flex justify-between">
                    <a href="/login" class="my-3 text-sm vstack-link">
                        Efetuar login
                    </a>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    props: ['token'],
    data() {
        return {
            loading: false,
            form: {
                password: '',
                confirm_password: '',
            },
        };
    },
    methods: {
        submit() {
            this.loading = true;
            this.$http
                .post(`/renew-password/${this.token}`, this.form)
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
