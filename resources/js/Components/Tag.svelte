<script>
    import route from '@/lib/route';
    import { inertia } from '@inertiajs/svelte';
    import cn from '@/lib/cn';
    import { getContrastText } from '@/lib/color';

    /**
     * @typedef {Object} Props
     * @property {any} tag
     * @property {string} [class]
     * @property {boolean} [showCount]
     */

    /** @type {Props} */
    let { tag, class: classname = '', showCount = false } = $props();
</script>

<a
    use:inertia
    href={route('tag.show', tag)}
    style={`background-color: ${tag.color};`}
    class={cn(
        'chip font-bold capitalize',
        getContrastText(tag.color),
        classname
    )}>
    {tag.name}
    {#if showCount}
        ({tag.reports_count})
    {/if}
</a>
