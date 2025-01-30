<script>
    import Page from '@/Components/Page.svelte';
    import CircleX from '@/Components/icons/CircleX.svelte';
    import { formatToInputDateString, route } from '@/lib';
    import {
        ErrorMessage,
        Label,
        Button,
        Field,
        Input,
    } from '@/Components/forms';
    import { router, useForm } from '@inertiajs/svelte';
    import { FileDropzone, getModalStore } from '@skeletonlabs/skeleton';

    const modalStore = getModalStore();

    let { event } = $props();

    let form = useForm({
        name: event.name,
        date: formatToInputDateString(event.date),
        location: event.location,
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

    function handleEventSubmit(e) {
        e.preventDefault();
        router.post(
            route('event.update', event.id),
            {
                _method: 'put',
                ...$form,
            },
            { forceFormData: true }
        );
    }

    // Let's use the same form for each source
    let renameSource = useForm({
        name: null,
    });
    function handleRenameSourceSubmit(e, source) {
        e.preventDefault();
        // And set the name on submit
        $renameSource.name = source.name;
        // Then submit to the proper source
        $renameSource.put(
            route('event.source.update', [source.event_id, source.id]),
            {}
        );
    }

    /** @type {import('@skeletonlabs/skeleton').ModalSettings} */
    const deleteModal = {
        type: 'confirm',
        title: 'Confirm Delete',
        body: 'Are you sure you want to delete this source? All attached stubs will be deleted regardless of completion.',
    };

    function handleDeleteSourceSubmit(source) {
        router.delete(
            route('event.source.destroy', [source.event_id, source.id]),
            {}
        );
    }
</script>

<Page header="Edit Event">
    <div class="card">
        <form method="POST" onsubmit={handleEventSubmit}>
            <Field {form} name="name" required />
            <Field {form} name="date" type="date" required />
            <Field {form} name="location" label="Location (Place, Url, Etc.)" />
            <div class="flex flex-col gap-2">
                <Label for="dropzone" class="capitalize">
                    Add Additional Audio Sources
                </Label>
                <FileDropzone
                    name="sources[]"
                    accept="audio/*"
                    multiple={true}
                    on:change={fileOnChange}>
                    {#snippet meta()}
                        Audio files accepted
                    {/snippet}
                </FileDropzone>
                {#each $form.sources as item, i}
                    <ErrorMessage
                        message={($form.errors['sources.' + i] || '').replace(
                            'sources.' + i,
                            item.name
                        )} />
                {/each}
                {#if $form.sources.length > 0}
                    <div>
                        <div class="flex justify-between">
                            <h3 class="font-bold">Files</h3>
                            <Button
                                type="button"
                                variant="danger"
                                onclick={handleRemoveAllFiles}>
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

            <div class="flex items-center justify-end p-3">
                <Button type="submit">Update</Button>
            </div>
        </form>
    </div>

    <section>
        <h3 class="my-4 text-xl">Manage Sources</h3>
        <div class="flex flex-col gap-5">
            {#each event.sources as source}
                <div class="card flex items-center justify-between">
                    <form
                        method="PUT"
                        onsubmit={(e) => handleRenameSourceSubmit(e, source)}
                        class="flex items-center gap-1">
                        <Button
                            href={route('event.source.scrub', [
                                source.event_id,
                                source.id,
                            ])}>Stub</Button>
                        <Input
                            id={'source-' + source.id}
                            bind:value={source.name} />
                        <Button type="submit">Rename</Button>
                    </form>
                    <audio controls class="z-0">
                        <source src={source.audio_url} type="audio/mpeg" />
                        Your browser does not support the audio element.
                    </audio>
                    <Button
                        variant="danger"
                        onclick={() =>
                            modalStore.trigger({
                                ...deleteModal,
                                response: (r) =>
                                    r && handleDeleteSourceSubmit(source),
                            })}>
                        Delete
                    </Button>
                </div>
            {/each}
        </div>
    </section>
</Page>
