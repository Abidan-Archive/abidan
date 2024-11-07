<script>
    import { inertia } from '@inertiajs/svelte';

    /**
     * @typedef {Object} Props
     * @property {any} current_page
     * @property {any} last_page
     * @property {number} [per_page]
     * @property {any} total
     * @property {any} next_page_url
     * @property {any} prev_page_url
     */

    /** @type {Props} */
    let {
        current_page,
        last_page,
        per_page = 20,
        total,
        next_page_url,
        prev_page_url,
    } = $props();
</script>

{#if total > per_page}
    <nav
        aria-label="Pagination Navigation"
        class="flex items-center justify-between">
        {#if current_page <= 1}
            <span
                class="relative inline-flex cursor-default items-center rounded-md border border-gray-300 bg-base-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500">
                &laquo; Previous
            </span>
        {:else}
            <a
                use:inertia
                href={prev_page_url}
                rel="prev"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-base-700 px-4 py-2 text-sm font-medium leading-5 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-700 focus:outline-none focus:ring active:bg-gray-900 active:text-gray-300">
                &laquo; Previous
            </a>
        {/if}

        {#if current_page < last_page}
            <a
                use:inertia
                href={next_page_url}
                rel="next"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-base-700 px-4 py-2 text-sm font-medium leading-5 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-700 focus:outline-none focus:ring active:bg-gray-900 active:text-gray-300">
                Next &raquo;
            </a>
        {:else}
            <span
                class="relative inline-flex cursor-default items-center rounded-md border border-gray-300 bg-base-700 px-4 py-2 text-sm font-medium leading-5 text-gray-500">
                Next &raquo;
            </span>
        {/if}
    </nav>
{/if}
