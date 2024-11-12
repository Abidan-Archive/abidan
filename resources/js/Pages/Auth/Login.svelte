<script>
    import { useForm, inertia } from '@inertiajs/svelte';

    import Button3D from '@/Components/Button3D.svelte';
    import DiscordLogo from '@/Components/DiscordLogo.svelte';
    import Page from '@/Components/Page.svelte';
    import { cn, recaptcha, route } from '@/lib';
    import { Button, Field, Label } from '@/Components/forms';

    let form = useForm({
        email: null,
        password: null,
        remember: false,
        recaptcha: null,
    });

    function submit(e) {
        e.preventDefault();
        recaptcha('login', (token) => {
            $form.recaptcha = token;
            $form.post(route('login'));
        });
    }
</script>

<Page class="w-full md:w-1/2" header="Login">
    <div class="card">
        <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
            <Field {form} name="email" autofocus required recaptcha />

            <Field
                {form}
                name="password"
                type="password"
                required
                autocomplete="current-password" />

            <div>
                <Label for="remember_me" class="inline-flex items-center gap-2">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class={cn(
                            'rounded border-gray-700 bg-gray-900 text-indigo-600 shadow-sm',
                            'focus:ring-indigo-600 focus:ring-offset-gray-800'
                        )}
                        bind:checked={$form.remember}
                        name="remember" />
                    <span class="text-sm text-gray-400">Remember Me</span>
                </Label>
            </div>

            <div class="flex items-baseline justify-between">
                <a
                    use:inertia
                    href={route('register')}
                    class={cn(
                        'rounded-md text-sm text-gray-400 underline hover:text-gray-100',
                        'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800'
                    )}>
                    Sign up
                </a>
                <div class="flex items-baseline justify-end gap-2">
                    {#if route().has('password.request')}
                        <a
                            use:inertia
                            href={route('password.request')}
                            class={cn(
                                'rounded-md text-sm text-gray-400 underline hover:text-gray-100',
                                'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800'
                            )}>
                            Forgot your password?
                        </a>
                    {/if}

                    <Button>Log in</Button>
                </div>
            </div>
        </form>
        <hr class="divider" />
        <div class="flex flex-col items-center gap-2">
            <p>Sign in with a third-party provider</p>
            <Button3D href={route('oauth.discord')} themeDeg="235deg">
                <DiscordLogo />
            </Button3D>
        </div>
    </div>
</Page>
