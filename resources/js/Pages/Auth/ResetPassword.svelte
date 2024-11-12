<script>
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route';
    import recaptcha from '@/lib/recaptcha';
    import { Field, Button } from '@/Components/forms';
    import { useForm } from '@inertiajs/svelte';

    let form = useForm('ResetPassword', {
        token: route().params.token,
        email: route().params.email,
        password: null,
        password_confirmation: null,
        recaptcha: null,
    });

    function submit(e) {
        e.preventDefault();
        recaptcha('password/update', (token) => {
            $form.recaptcha = token;
            $form.post(route('password.update'));
        });
    }
</script>

<Page class="w-full md:w-1/2" header="Reset Password">
    <div class="card">
        <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
            <Field {form} name="email" autofocus required recaptcha />
            <Field {form} name="password" type="password" required />
            <Field
                {form}
                name="password_confirmation"
                type="password"
                label="Confirm Password"
                required />

            <div class="flex items-center justify-end">
                <Button>Reset Password</Button>
            </div>
        </form>
    </div>
</Page>
