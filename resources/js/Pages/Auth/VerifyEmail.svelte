<script>
    import Page from '@/Components/Page.svelte';
    import route from '@/lib/route';
    import { Button } from '@/Components/forms';
    import { router } from '@inertiajs/svelte';

    function resend(e) {
        e.preventDefault();
        router.post(route('verification.send'));
    }
    function logout(e) {
        e.preventDefault();
        router.post(route('logout'));
    }
</script>

<Page class="w-full md:w-1/2" header="Email Verification">
    <div class="card flex flex-col gap-4">
        <div class="text-sm text-gray-400">
            Thanks for signing up! Before getting started, could you verify your
            email address by clicking on the link we just emailed to you? If you
            didn't receive the email, we will gladly send you another.
        </div>

        <div class="flex items-center justify-between">
            <form method="POST" onsubmit={resend}>
                <div>
                    <Button>Resend Verification Email</Button>
                </div>
            </form>

            <form method="POST" onsubmit={logout}>
                <button
                    type="submit"
                    class="focus:ring=2 rounded-md text-sm text-gray-400 underline hover:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</Page>
