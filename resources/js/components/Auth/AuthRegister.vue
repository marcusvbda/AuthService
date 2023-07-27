<template>
    <div
        class="w-10/12 flex flex-col py-5"
        v-loading="loading"
        element-loading-text="Verificando credenciais"
    >
        <text-logo />
        <b class="mt-4 dark:text-neutral-200">Cadastro de usuário</b>
        <small class="dark:text-neutral-300">
            Preencha os campos e verifique seu email para ter acesso ao sistema.
        </small>
        <form v-on:submit.prevent="submit" class="vstack-form">
            <div class="flex mt-8 justify-center">
                <el-radio-group v-model="form.plan">
                    <el-radio-button label="lite">Lite</el-radio-button>
                    <el-radio-button label="interprise">
                        Interprise
                    </el-radio-button>
                    <el-radio-button label="premium">Premium</el-radio-button>
                </el-radio-group>
            </div>
            <div class="flex flex-col mt-2">
                <label class="form-label">Nome</label>
                <input class="form-input" v-model="form.name" required />
            </div>
            <div class="flex flex-col mt-2">
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
                <button class="vstack-btn primary">Efetuar cadastro</button>
                <div class="flex justify-between">
                    <a href="/login" class="my-3 text-sm vstack-link">
                        Já tenho uma conta
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
                plan: 'premium',
                name: '',
                email: '',
                password: '',
                confirm_password: '',
            },
        };
    },
    methods: {
        submit() {
            this.loading = true;
            this.$http
                .post(`/register`, this.form)
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
