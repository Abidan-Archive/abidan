<script>
    import { inertia, page, router } from '@inertiajs/svelte';
    import { AppBar, popup } from '@skeletonlabs/skeleton';
    import Logo from '@/Components/Logo.svelte';
    import { Hamburger, User } from '@/Components/icons';
    import route from '@/lib/route';
    import Navigation from '@/Components/Navigation.svelte';
    import { getDrawerStore } from '@skeletonlabs/skeleton';

    const drawerStore = getDrawerStore();

    function openLeftNavSidebar() {
        drawerStore.open({
            width: 'w-64',
            rounded: 'rounded-none',
        });
    }
    /** @type {import('@skeletonlabs/skeleton').PopupSettings} */
    const authPopupNav = {
        event: 'click',
        target: 'authPopup',
        placement: 'bottom',
    };

    function onLogout(e) {
        e.preventDefault();
        router.post(route('logout'));
    }
</script>

<AppBar
    border="lg:border-l-5 border-primary-500"
    background="bg-surface-500"
    padding="py-2 px-4"
    gridColumns="lg:grid-cols-2 grid-cols-3"
    slotLead="lg:hidden"
    slotDefault="place-self-center lg:place-self-start"
    slotTrail="place-self-end h-full flex items-center">
    {#snippet lead()}
        <button
            class="btn-icon btn-sm !bg-transparent"
            onclick={openLeftNavSidebar}
            aria-label="open navigation menu">
            <Hamburger />
        </button>
    {/snippet}

    <a use:inertia href={route('home')}>
        <div class="flex h-full flex-row items-center justify-start gap-2">
            <Logo class="hidden sm:block" />
            <h1
                class="shrink-0 border-b-2 border-primary-500 text-xl sm:text-2xl md:border-b-0">
                Abidan Archive
            </h1>
        </div>
    </a>

    {#snippet trail()}
        <Navigation class="hidden lg:block" />
        <div>
            {#if !!$page.props.auth?.user}
                <button
                    type="button"
                    class="flex gap-2"
                    use:popup={authPopupNav}>
                    <User class="hidden h-6 w-6 sm:inline" />
                    {$page.props.auth.user.username}
                </button>
                <div data-popup="authPopup">
                    <nav class="card list-nav z-50 w-48 shadow-xl">
                        <ul>
                            <li>
                                <a
                                    use:inertia
                                    href={route(
                                        'user.show',
                                        $page.props.auth.user
                                    )}>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a
                                    use:inertia
                                    href={route(
                                        'user.edit',
                                        $page.props.auth.user
                                    )}>
                                    Settings
                                </a>
                            </li>
                            <hr />
                            <li>
                                <form method="post" onsubmit={onLogout}>
                                    <button type="submit" class="w-full">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            {:else}
                <a use:inertia href={route('login')} class="flex gap-2">
                    <User class="hidden h-6 w-6 sm:block" />
                    Login
                </a>
            {/if}
        </div>
    {/snippet}
</AppBar>
