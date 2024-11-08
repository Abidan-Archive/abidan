<script>
    import cn from '@/lib/cn';
    /**
     * @typedef {Object} Props
     * @property {string} [class]
     * @property {string} [href]
     * @property {string} [target]
     * @property {string} [ref]
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props & { [key: string]: any }} */
    let {
        class: className = '',
        href = '',
        target = '_blank',
        ref = 'noreferrer',
        children,
        ...rest
    } = $props();
</script>

{#if href}
    <a
        {...rest}
        {href}
        {target}
        {ref}
        class={cn(`text-on-surface-token hover:text-surface-300`, className)}>
        {@render children?.()}
    </a>
{:else}
    {@render children?.()}
{/if}
