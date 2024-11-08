<script>
    import cn from '@/lib/cn';

    /**
     * @typedef {Object} Props
     * @property {string} [class]
     * @property {boolean} [large]
     * @property {boolean} [marked]
     * @property {boolean} [light]
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props & { [key: string]: any }} */
    let {
        class: className = '',
        large = false,
        marked = false,
        light = false,
        children,
        ...rest
    } = $props();
</script>

<button
    class={cn(
        'flex items-center justify-center self-center rounded-full border border-white p-2 text-center text-2xl',
        { 'h-10 w-10': !large, 'h-12 w-12': !!large },
        {
            'bg-typo-500 hover:bg-typo-600': !marked,
            'bg-red-600 hover:bg-red-700': !!marked,
        },
        { 'text-black': !light, 'text-typo-500': !!light },
        className
    )}
    {...rest}>
    {@render children?.()}
</button>
