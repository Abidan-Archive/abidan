<!-- @migration-task Error while migrating Svelte code: This migration would change the name of a slot making the component unusable -->
<script>
    import { inertia } from '@inertiajs/svelte';
    import cn from '@/lib/cn';

    /**
     * @typedef {Object} Props
     * @property {string|import('svelte').Snippet|null} [header]
     * @property {string} [class]
     * @property {string|null} [edit]
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props} */
    let {
        header = '',
        class: className = '',
        edit = null,
        children,
    } = $props();
</script>

<article class={cn('container mx-auto my-4 h-full px-4', className)}>
    {#if !!header}
        {#if typeof header === 'string'}
            {#if edit}
                <div class="flex items-baseline justify-between">
                    <h2 class="text-3xl">{header}</h2>
                    <a use:inertia href={edit} class="text-xl">Edit</a>
                </div>
            {:else}
                <h2 class="text-3xl">{header}</h2>
            {/if}
        {:else}
            {@render header()}
        {/if}
        <hr />
    {/if}
    {@render children?.()}
</article>
