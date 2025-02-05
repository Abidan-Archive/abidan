<script>
    import { router } from '@inertiajs/svelte';
    import cn from '@/lib/cn.js';
    import route from '@/lib/route.js';
    import Lens from '@/Components/icons/Lens.svelte';
    import SortBy from '@/Components/SortBy.svelte';
    import TagFilter from '@/Components/TagFilter.svelte';
    import pickBy from 'lodash/pickBy';

    /**
     * @typedef {Object} Props
     * @property {any} class
     * @property {boolean} [advanced]
     * @property {any} [tags]
     */

    /** @type {Props} */
    let { class: className, advanced = false, tags = [] } = $props();

    const urlParams = new URLSearchParams(window.location.search);
    let query = $state(urlParams.get('query') || '');
    let includeTags = $state(urlParams.get('includeTags')?.split(',') || []);
    let excludeTags = $state(urlParams.get('excludeTags')?.split(',') || []);
    const orderBy = urlParams.get('orderBy')?.split(',') || null;
    let sortState = $state(
        orderBy
            ? {
                  key: orderBy[0],
                  direction: orderBy[1] == 'ASC' ? 1 : -1,
              }
            : null
    );

    function submit(e) {
        e.preventDefault();
        const values = pickBy({
            query,
            includeTags: includeTags.join(','),
            excludeTags: excludeTags.join(','),
            orderBy: sortState
                ? `${sortState.key},${sortState.direction > 0 ? 'ASC' : 'DESC'}`
                : null,
        });

        router.get(route('search'), values);
    }
</script>

<form class={cn('flex flex-col gap-2', className)} onsubmit={submit}>
    <div class="group flex h-14 w-full items-start justify-center">
        <div
            class="relative m-0 h-full flex-1 border-2 border-primary-500 bg-black p-1.5 shadow-[0_0_0_0.10rem] shadow-transparent transition-all duration-500 focus-within:shadow-surface-500 group-hover:border-tertiary-500 group-hover:shadow-surface-500 group-active:border-tertiary-500 group-active:shadow-surface-500">
            <label for="simple-search" class="sr-only">Search</label>
            <input
                type="search"
                bind:value={query}
                id="simple-search"
                placeholder="[Information Requested]"
                class="dark:[dark-color-scheme] group m-0 h-full w-full rounded-sm border-none bg-surface-900 text-center ring-0 placeholder:text-center focus:border-none focus:ring-0" />
        </div>
        <button
            type="submit"
            class="z-0 flex h-full w-20 items-center justify-center bg-primary-500 px-3 py-2 transition-colors duration-500 group-hover:bg-tertiary-500 group-active:bg-tertiary-500">
            <Lens class="block h-5 w-5 text-on-surface-token" />
            <span class="sr-only">Search Button</span>
        </button>
    </div>
    {#if advanced}
        <div class="flex flex-col justify-between gap-2 md:flex-row">
            {#if tags.length > 0}
                <TagFilter
                    bind:includeValue={includeTags}
                    bind:excludeValue={excludeTags}
                    availableTags={tags}
                    class="flex-1" />
            {/if}
            <div>
                <SortBy
                    class="w-auto"
                    bind:value={sortState}
                    options={[
                        { value: 'id', label: 'ID' },
                        { value: 'date', label: 'Date' },
                        { value: 'likes', label: 'Likes' },
                    ]}
                    placeholder="Sort" />
            </div>
        </div>
    {/if}
</form>

<style lang="postcss">
    input[type='search']::-webkit-search-cancel-button {
        @apply dark:invert;
    }
</style>
