<script>
    import {
        ErrorMessage,
        Field,
        Label,
        Button,
        Input,
    } from '@/Components/forms';
    import TagFilter from '@/Components/TagFilter.svelte';

    let { form, tags, eventSection } = $props();

    function addDialogue() {
        $form.dialogues = [...$form.dialogues, { speaker: '', line: '' }];
    }
</script>

<div class="card flex flex-col gap-4">
    <h3>Dialogue</h3>
    {#each $form.dialogues as dialogue, idx}
        {#if idx > 0}
            <hr class="m-0" />
        {/if}
        <div class="flex flex-col gap-2">
            <Label for={'speaker-' + idx} class="capitalize">Speaker</Label>
            <Input
                id={'speaker-' + idx}
                type="text"
                class="input block w-full"
                bind:value={dialogue.speaker} />
            <ErrorMessage message={$form.errors[`dialogues.${idx}.speaker`]} />
        </div>
        <div class="flex flex-col gap-2">
            <Label for={'line-' + idx} class="capitalize">Line</Label>
            <textarea
                id={'line-' + idx}
                class="textarea block w-full"
                bind:value={dialogue.line}></textarea>
            <ErrorMessage
                message={($form.errors[`dialogues.${idx}.line`] || '').replace(
                    `dialogues.${idx}.line`,
                    'line'
                )} />
        </div>
    {/each}
    <div class="flex items-center justify-end gap-4">
        <small> Dialogues with no speaker and line will be pruned. </small>
        <Button class="item-right" onclick={addDialogue} type="button">
            Add another
        </Button>
    </div>
</div>
{#if eventSection}
    <div class="card flex flex-col gap-4">
        {@render eventSection()}
    </div>
{/if}
<div class="card flex flex-col gap-4">
    <h3>Metadata</h3>

    <Field {form} name="footnote" />
    <div class="grid gap-4 sm:grid-cols-2">
        <Field {form} name="source_label" label="Source Name" />
        <Field {form} name="source_href" label="Source URL" />
    </div>

    <Field {form} name="date" type="date" required />

    <div class="flex flex-col gap-2">
        <Label for="tagFilter" class="capitalize">Tags</Label>
        <TagFilter
            bind:includeValue={$form.tags}
            availableTags={tags}
            onlyInclude />
    </div>
</div>
