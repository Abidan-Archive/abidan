<script>
    import cn from '@/lib/cn';
    import Oddment from '@/lib/oddment';
    let { form, class: className } = $props();

    const oddment = new Oddment({
        'The form has errors': 20,
        'Failed Validations': 10,
        'Not Again...': 5,
        'Gosh... How do I say this?': 5,
        'Big Oof': 3,
        'Small Oof': 3,
        'lol what?': 10,
    });
</script>

{#if Object.keys($form.errors).length > 0}
    <div
        class={cn(
            'card variant-ghost-error w-full border p-4 shadow-md',
            className
        )}>
        <h4 class="bold h4 text-red-400">{oddment.pick()}</h4>
        <ul class="list-disc space-y-1 pl-4 text-sm text-red-400">
            {#each [...new Set(Object.values($form.errors))] as error}
                <li>{error}</li>
            {/each}
        </ul>
    </div>
{/if}
