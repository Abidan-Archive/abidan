<script>
    import cn from '@/lib/cn';
    /**
     * @typedef {Object} Props
     * @property {any} [value]
     * @property {any} for
     * @property {string} [class]
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props & { [key: string]: any }} */
    let {
        value = null,
        for: forInput,
        class: className = '',
        children,
        ...rest
    } = $props();
</script>

<label
    for={forInput}
    class={cn('block text-sm font-medium text-gray-300', className)}
    {...rest}>
    {#if !!value}
        {value}
    {:else}
        {@render children?.()}
    {/if}
</label>
