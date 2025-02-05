<script>
    import { inertia } from '@inertiajs/svelte';
    import { Previous, Next } from '@/Components/icons';

    /**
     * @typedef {Object} Props
     * @property {any} current_page
     * @property {any} last_page
     * @property {number} [per_page]
     * @property {any} from
     * @property {any} to
     * @property {any} total
     * @property {any} next_page_url
     * @property {any} prev_page_url
     * @property {any} links
     * @property {boolean} [maxResultDisclaimer]
     */

    /** @type {Props} */
    let {
        current_page,
        last_page,
        per_page = 20,
        from,
        to,
        total,
        next_page_url,
        prev_page_url,
        links,
        maxResultDisclaimer = false,
    } = $props();
</script>

{#if total > per_page}
    <nav
        aria-label="Pagination Navigation"
        class="flex items-center justify-between">
        <div class="flex flex-1 justify-between sm:hidden">
            {#if current_page <= 1}
                <span
                    class="relative inline-flex cursor-default items-center rounded-md border bg-base-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500">
                    &laquo; Previous
                </span>
            {:else}
                <a
                    use:inertia
                    href={prev_page_url}
                    class="relative inline-flex items-center rounded-md border bg-base-700 px-4 py-2 text-sm font-medium leading-5 text-gray-300 no-underline ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-100">
                    &laquo; Previous></a>
            {/if}

            {#if current_page < last_page}
                <a
                    use:inertia
                    href={next_page_url}
                    class="relative ml-3 inline-flex items-center rounded-md border bg-base-700 px-4 py-2 text-sm font-medium leading-5 text-gray-300 no-underline ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-300"
                    >Next &raquo;</a>
            {:else}
                <span
                    class="relative ml-3 inline-flex cursor-default items-center rounded-md border bg-base-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500"
                    >Next &raquo;</span>
            {/if}
        </div>

        <div
            class="hidden sm:flex sm:flex-1 sm:flex-col sm:items-center sm:justify-between sm:gap-y-2">
            <div>
                <p class="text-sm leading-5 text-gray-300">
                    Showing
                    {#if current_page > 1}
                        <span class="font-medium">{from}</span>
                        to
                        <span class="font-medium">{to}</span>
                    {:else}
                        {per_page}
                    {/if}
                    of
                    <span class="font-medium">{total}</span>
                    results
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {#if current_page <= 1}
                        <span aria-disabled="true" aria-label="Previous">
                            <span
                                class="relative inline-flex cursor-default items-center rounded-l-md border bg-base-700 px-2 py-2 text-sm font-medium leading-5 text-gray-500"
                                aria-hidden="true">
                                <Previous />
                            </span>
                        </span>
                    {:else}
                        <a
                            use:inertia
                            href={prev_page_url}
                            rel="prev"
                            aria-label="Previous"
                            class="relative -ml-px inline-flex items-center rounded-l-md border bg-base-700 px-2 py-2 text-sm font-medium leading-5 text-gray-200 no-underline ring-gray-300 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-500 active:text-gray-100">
                            <Previous />
                        </a>
                    {/if}

                    {#each links.slice(1, links.length - 1) as link}
                        {#if link.label === '...'}
                            <span
                                aria-disabled="true"
                                class="relative -ml-px inline-flex cursor-default items-center border bg-base-700 px-4 py-2 text-sm font-medium leading-5 text-gray-300"
                                >...</span>
                        {:else if link.active}
                            <span
                                aria-current="page"
                                class="relative -ml-px inline-flex cursor-default items-center border bg-base-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500"
                                >{link.label}</span>
                        {:else}
                            <a
                                use:inertia
                                href={link.url}
                                aria-label={`Go to page ${link.label}`}
                                class="relative -ml-px inline-flex items-center border bg-base-700 px-4 py-2 text-sm font-medium leading-5 text-gray-300 no-underline ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-300">
                                {link.label}
                            </a>
                        {/if}
                    {/each}

                    <!-- Next Page Link -->
                    {#if current_page < last_page}
                        <a
                            use:inertia
                            href={next_page_url}
                            rel="next"
                            aria-label="Next"
                            class="relative -ml-px inline-flex items-center rounded-r-md border bg-base-700 px-2 py-2 text-sm font-medium leading-5 text-gray-200 no-underline ring-gray-300 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-500 active:text-gray-100">
                            <Next />
                        </a>
                    {:else}
                        <span aria-disabled="true" aria-label="Next">
                            <span
                                class="relative inline-flex cursor-default items-center rounded-r-md border bg-base-700 px-2 py-2 text-sm font-medium leading-5 text-gray-500"
                                aria-hidden="true">
                                <Next />
                            </span>
                        </span>
                    {/if}
                </span>
            </div>
        </div>
    </nav>
    {#if maxResultDisclaimer && total === 1000}
        <small class="mx-auto"
            >Search is limited to a max of 1000 results but all reports are
            searched.</small>
    {/if}
{/if}
