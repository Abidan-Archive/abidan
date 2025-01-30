<script>
    import { Autocomplete, popup } from '@skeletonlabs/skeleton';
    import cn from '@/lib/cn';

    /**
     * @typedef {Object} Props
     * @property {string} [id]
     * @property {string} [name]
     * @property {string} [class]
     * @property {string} [popupClass]
     * @property {string} [type]
     * @property {any} value
     * @property {{value, label}} searchValue
     * @property {function} onsearch
     * @property {string} [placement]
     */

    /** @type {Props & { [key: string]: any }} */
    let {
        id = 'autocomplete',
        class: className = '',
        popupClass: popupClassName = '',
        name = 'search',
        type = 'search',
        value = $bindable(),
        searchOptions = $bindable([]),
        onsearch = () => undefined,
        placement = 'bottom',
        onselect = () => undefined,
        ...rest
    } = $props();

    const setType = (node) => (node.type = type);
    let target = $derived('popup' + id[0].toUpperCase() + id.slice(1));

    let searchValue = $state();
    function onInput() {
        if (searchValue) {
            onsearch(searchValue);
        } else {
            searchOptions = [];
        }
    }
    function select(event) {
        searchValue = event.detail.label;
        value = event.detail.value;
        onselect(event.detail.value);
    }
</script>

<input
    {id}
    {name}
    bind:value={searchValue}
    oninput={onInput}
    use:setType
    class={cn(
        'autocomplete input',
        'rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm',
        'focus:border-indigo-600 focus:ring-indigo-600',
        className
    )}
    {...rest}
    use:popup={{
        event: 'focus-click',
        target,
        placement,
    }} />
<div data-popup={target} class="z-10">
    <div
        class={cn(
            'card mx-auto max-h-48 w-full max-w-sm overflow-y-auto p-4',
            popupClassName
        )}
        tabindex="-1">
        <Autocomplete
            bind:input={searchValue}
            options={searchOptions}
            on:selection={select} />
    </div>
</div>
