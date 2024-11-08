<script>
    import cn from '@/lib/cn';
    import { ChevronUp, ChevronDown, ChevronUpDown } from '@/Components/icons';

    /**
     * @typedef {Object} Props
     * @property {any} value
     * @property {string} [class]
     * @property {any} [options] - {value, label}
     * @property {any} placeholder
     */

    /** @type {Props} */
    let {
        value = $bindable(),
        class: className = '',
        options = [],
        placeholder,
    } = $props();
    let sortKey = $state();

    const directions = [
        { icon: ChevronUpDown, value: 0, label: 'No Sort Direction' },
        { icon: ChevronUp, value: 1, label: 'Ascending Sort' },
        { icon: ChevronDown, value: -1, label: 'Descending Sort' },
    ];
    let idx = $state(0); // Direction index, internal use only
    const Icon = $derived(directions[idx].icon);

    $effect(() => {
        if (idx !== 0 && !!sortKey) {
            value = { key: sortKey, direction: directions[idx].value };
        } else {
            value = null;
        }
    });

    function cycleDirections() {
        idx = (idx + 1) % directions.length;
    }
</script>

<div class="flex">
    <label for="sortby-select" class="sr-only">Sort by select</label>
    <select
        id="sortby-select"
        bind:value={sortKey}
        class={cn(
            'peer block w-full appearance-none border-0 bg-transparent bg-none px-0 py-2.5 text-gray-300 focus:outline-none focus:ring-0',
            className
        )}>
        {#if placeholder}
            <option disabled selected>{placeholder}</option>
        {/if}
        {#each options as option}
            <option value={option.value}>{option.label}</option>
        {/each}
    </select>
    <button type="button" class="px-2" onclick={cycleDirections}>
        <span class="sr-only">{directions[idx].label}</span>
        <Icon />
    </button>
</div>
