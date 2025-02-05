<script>
    import { fly, scale } from 'svelte/transition';
    import { onMount } from 'svelte';
    import { flip } from 'svelte/animate';
    import {
        Autocomplete,
        prefersReducedMotionStore,
        popup,
    } from '@skeletonlabs/skeleton';

    import cn from '@/lib/cn.js';

    /**
     * @typedef {Object} Props
     * @property {string} [input]
     * @property {any} [includeValue]
     * @property {any} [excludeValue]
     * @property {booean} [onlyInclude]
     * @property {any} [availableTags]
     * @property {boolean} [required]
     * @property {string} [class]
     * @property {boolean} [disabled]
     * @property {any} [oninput]
     * @property {any} [onkeypress]
     * @property {any} [onkeydown]
     * @property {any} [onkeyup]
     */

    /** @type {Props & { [key: string]: any }} */
    let {
        input = $bindable(''),
        includeValue = $bindable([]),
        excludeValue = $bindable([]),
        onlyInclude = false,
        availableTags = [],
        required = false,
        class: className = '',
        disabled = false,
        oninput = () => undefined,
        onkeypress,
        onkeydown,
        onkeyup,
        name,
        ...rest
    } = $props();

    let duration = 150;

    /** @type {import('@skeletonlabs/skeleton').PopupSettings} */
    let popupSettings = {
        event: 'focus-click',
        target: 'popupAutocomplete',
        placement: 'bottom',
    };

    let inputValid = $state(true);
    let chipValues = $state([
        ...(includeValue || []).map((val) => ({
            val: val,
            id: Math.random(),
            include: true,
        })),
        ...(excludeValue || []).map((val) => ({
            val: val,
            id: Math.random(),
            include: false,
        })),
    ]);

    function safeAnimate(node, options) {
        if (!$prefersReducedMotionStore) {
            return options.fn(node, options);
        }
    }

    // Reset form
    function resetFormHandler() {
        includeValue = [];
        excludeValue = [];
    }
    let includeSelectElement = $state();
    onMount(() => {
        // Verify external form is present
        if (!includeSelectElement.form) return;

        const externalForm = includeSelectElement.form;

        return () => {
            externalForm.removeEventListener('reset', resetFormHandler);
        };
    });

    function onInputHandler() {
        inputValid = true;
        oninput();
    }

    function validateInTags(chip) {
        return availableTags.includes(chip);
    }
    // what
    function validate(chip = '') {
        if (!chip) return false;
        chip = chip.trim();
        if (chip === '') return false;
        return validateInTags(chip);
    }

    function addChipCommon(chip, include) {
        if (include) {
            includeValue.push(chip);
            includeValue = includeValue;
        } else {
            excludeValue.push(chip);
            excludeValue = excludeValue;
        }
        chipValues.push({ val: chip, id: Math.random(), include });
        chipValues = chipValues;
    }

    function removeChipCommon(chip, include) {
        if (include) {
            let chipIndex = includeValue.indexOf(chip);
            includeValue.splice(chipIndex, 1);
            includeValue = includeValue;
        } else {
            let chipIndex = excludeValue.indexOf(chip);
            excludeValue.splice(chipIndex, 1);
            excludeValue = excludeValue;
        }
        chipValues = chipValues.filter((o) => o.val !== chip);
    }

    function onSubmit(event) {
        event.preventDefault();
        addChip(input);
    }

    function addChip(val) {
        inputValid = validate(val);
        if (inputValid === false) return;

        let included = includeValue.includes(val);
        let excluded = excludeValue.includes(val);
        if (!included && !excluded) {
            // If not in include or exclude, include it
            addChipCommon(val, true);
        } else {
            if (onlyInclude) {
                removeChipCommon(val, true);
            } else {
                if (included) {
                    // If in include, remove from include and exclude it
                    removeChipCommon(val, true);
                    addChipCommon(val, false);
                } else {
                    // If in exclude, remove from exclude and include it
                    removeChipCommon(val, false);
                    addChipCommon(val, true);
                }
            }
        }

        input = '';
    }

    function onChipClick(val) {
        if (disabled) return;
        if (onlyInclude) {
            removeChipCommon(val, true);
            return;
        }
        if (excludeValue.includes(val)) {
            removeChipCommon(val, false);
            return;
        }
        removeChipCommon(val, true);
        addChipCommon(val, false);
    }

    function onAutocompleteSelect(event) {
        addChip(event.detail.value);
    }
</script>

<div class={cn('flex flex-col gap-2', className)}>
    <div
        class={cn(
            'input-chip textarea cursor-pointer p-2 rounded-container-token',
            !inputValid && 'input-error'
        )}
        class:opacity-50={disabled}>
        <!-- NOTE: Don't use `hidden` as it prevents `required` from operating -->
        <div class="h-0 overflow-hidden">
            <select
                bind:this={includeSelectElement}
                bind:value={includeValue}
                name="includeTags"
                multiple
                {required}
                aria-label="tag select"
                tabindex="-1">
                {#each includeValue as option}
                    <option value={option}>{option}</option>
                {/each}
            </select>
            <select
                bind:value={excludeValue}
                name="excludeTags"
                multiple
                {required}
                aria-label="tag select"
                tabindex="-1">
                {#each excludeValue as option}
                    <option value={option}>{option}</option>
                {/each}
            </select>
        </div>
        <!-- Chip Wrapper -->
        <div class="input-chip-wrapper space-y-4">
            <!-- Input Field -->
            <form onsubmit={onSubmit}>
                <input
                    {name}
                    type="text"
                    bind:value={input}
                    placeholder="Filter Tags..."
                    class="input-chip-field autocomplete unstyled w-full border-0 bg-transparent p-0 !ring-0"
                    oninput={onInputHandler}
                    {disabled}
                    use:popup={popupSettings}
                    {...rest} />
            </form>
            <!-- Chip List -->
            {#if chipValues.length}
                <div
                    class="input-chip-list flex flex-wrap gap-2"
                    in:safeAnimate|local={{
                        fn: fly,
                        duration,
                        opacity: 0,
                        y: -20,
                    }}
                    out:safeAnimate|local={{
                        fn: fly,
                        duration,
                        opacity: 0,
                        y: -20,
                    }}>
                    {#each chipValues as { id, val, include } (id)}
                        <!-- Wrapping div require for FLIP animation -->
                        <div animate:flip={{ duration }}>
                            <button
                                type="button"
                                class={cn(
                                    'chip',
                                    include
                                        ? 'variant-filled'
                                        : 'variant-filled-error'
                                )}
                                onclick={() => onChipClick(val)}
                                {onkeypress}
                                {onkeydown}
                                {onkeyup}
                                in:safeAnimate|local={{
                                    fn: scale,
                                    duration,
                                    opaciy: 0,
                                }}
                                out:safeAnimate|local={{
                                    fn: scale,
                                    duration,
                                    opacity: 0,
                                }}>
                                <span>{val}</span>
                                <span>x</span>
                            </button>
                        </div>
                    {/each}
                </div>
            {/if}
        </div>
    </div>
    <!-- Move to below thing thing because it covers when chip is there  -->
    <div data-popup="popupAutocomplete" class="z-10">
        <div
            class="card mt-4 max-h-48 w-full max-w-sm overflow-y-auto p-4"
            tabindex="-1">
            <Autocomplete
                bind:input
                options={availableTags.map((tag) => ({
                    label: tag,
                    value: tag,
                }))}
                on:selection={onAutocompleteSelect} />
        </div>
    </div>
</div>
