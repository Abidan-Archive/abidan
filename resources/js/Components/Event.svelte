<script>
    import Oddment from '@/lib/oddment.js';
    import isValidUrl from '@/lib/url';
    import route from '@/lib/route';
    import { Link } from '@/Components/icons';
    import { inertia } from '@inertiajs/svelte';
    import { getToastStore } from '@skeletonlabs/skeleton';
    import cn from '@/lib/cn';

    const toastStore = getToastStore();

    const copyVerbs = new Oddment({
        copied: 20,
        'violently thrown': 5,
        'gently placed': 5,
    });

    const formattedDate = new Date(event.date).toLocaleDateString('en-US');
    async function copyLinkClicked() {
        await navigator.clipboard.writeText(route('event.show', event));
        toastStore.trigger({
            message: `Event link ${copyVerbs.pick()} into your clipboard.`,
        });
    }

    /**
     * @typedef {Object} Props
     * @property {any} event
     * @property {string} [class]
     */

    /** @type {Props} */
    let { event, class: className = '' } = $props();
</script>

<article id={event.id} class={cn('card', className)}>
    <section class="mb-4 flex justify-between">
        <div>
            <h3 class="h3">
                <a
                    class="font-bold text-white no-underline hover:underline"
                    use:inertia={{ prefetch: true }}
                    href={route('event.show', event)}
                    >#{event.id}
                    &middot; <span>{event.name}</span>
                    &middot; <span>{formattedDate}</span>
                </a>
            </h3>
        </div>
        <div class="flex items-center text-sm">
            <button
                type="button"
                class="flex hover:underline hover:underline-offset-4"
                onclick={copyLinkClicked}>
                <Link class="inline" />
                <span class="pl-1">Link</span>
            </button>
        </div>
    </section>
    <section class="flex justify-between">
        <span class="hidden text-sm text-typo-600 sm:flex">
            Source:
            {#if event.location && isValidUrl(event.location)}
                <a href={event.location} target="_blank" class="pl-1">
                    {event.location}
                </a>
            {:else}
                {event.location || 'n/a'}
            {/if}
        </span>
        <span class="text-sm text-typo-600">
            {event.reports_count || 0} reports
        </span>
    </section>
</article>
