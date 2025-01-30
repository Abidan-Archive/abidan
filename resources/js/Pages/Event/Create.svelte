<script>
    import Page from '@/Components/Page.svelte';
    import CircleX from '@/Components/icons/CircleX.svelte';
    import route from '@/lib/route';
    import {
        ErrorBanner,
        ErrorMessage,
        Label,
        Button,
        Field,
    } from '@/Components/forms';
    import { useForm } from '@inertiajs/svelte';
    import { FileDropzone, ProgressBar } from '@skeletonlabs/skeleton';

    let form = useForm({
        name: null,
        date: null,
        location: null,
        sources: [],
    });

    function fileOnChange(e) {
        $form.sources = Array.from(e.target.files);
    }

    function handleRemoveFile(index) {
        $form.sources = $form.sources.filter((_, i) => i !== index);
    }

    function handleRemoveAllFiles() {
        $form.sources = [];
    }

    function submit(e) {
        e.preventDefault();
        $form.post(route('event.store'), { forceFormData: true });
    }
</script>

<Page header="Create Event">
    <div class="card">
        <ErrorBanner {form} />
        <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
            <Field {form} name="name" required />
            <Field {form} name="date" type="date" required />
            <Field {form} name="location" label="Location (Place, Url, Etc.)" />
            <div class="flex flex-col gap-2">
                <Label for="sources" class="capitalize">Audio Source</Label>
                <FileDropzone
                    name="sources[]"
                    accept="audio/*"
                    multiple={true}
                    on:change={fileOnChange}>
                    {#snippet meta()}
                        Audio files accepted
                    {/snippet}
                </FileDropzone>
                {#if $form.progress}
                    <ProgressBar value={$form.progress.percentage} max="100" />
                {/if}
                {#each $form.sources as item, i}
                    <ErrorMessage
                        message={($form.errors['sources.' + i] || '').replace(
                            'sources.' + i,
                            item.name
                        )} />
                {/each}
                {#if $form.sources.length > 0}
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold">Files</h3>
                            <Button
                                type="button"
                                variant="danger"
                                onclick={handleRemoveAllFiles}
                                aria-label="Remove all files">
                                Remove All
                            </Button>
                        </div>
                        <ul>
                            {#each $form.sources as item, i}
                                <li class="flex items-center">
                                    <span>{item.name}</span>
                                    <button
                                        type="button"
                                        class="pl-2 text-red-400"
                                        aria-label="Remove File"
                                        onclick={() => handleRemoveFile(i)}>
                                        <CircleX />
                                    </button>
                                </li>
                            {/each}
                        </ul>
                    </div>
                {/if}
            </div>

            <div class="flex items-center justify-end">
                <Button type="submit">Create</Button>
            </div>
        </form>
    </div>
</Page>

<style lang="postcss">
    input[type='date']::-webkit-calendar-picker-indicator {
        @apply dark:invert;
    }
</style>
