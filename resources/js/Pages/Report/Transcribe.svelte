<script>
    import { useForm } from '@inertiajs/svelte';
    import Page from '@/Components/Page.svelte';
    import {
        ErrorMessage,
        Field,
        Label,
        Button,
        Input,
    } from '@/Components/forms';
    import AudioPlayer from '@/Components/audio/AudioPlayer.svelte';

    let { stub } = $props();
    let form = useForm({
        dialogues: [
            {
                speaker: 'Speaker',
                line: '',
            },
            {
                speaker: 'Will Wight',
                line: '',
            },
        ],
        footnote: '',
        tags: [],
    });

    function addSpeaker() {
        $form.dialogues = [
            ...$form.dialogues,
            { speaker: 'Speaker', line: '' },
        ];
    }

    function submit(e) {
        e.preventDefault();
    }
</script>

<Page header="Transcribe Report">
    <form method="POST" onsubmit={submit} class="flex flex-col gap-4">
        <div class="flex justify-center">
            <AudioPlayer src={stub.audio_url} />
        </div>
        <div class="card flex flex-col gap-4">
            <h3>Dialogue</h3>
            {#each $form.dialogues as dialogue, idx}
                <div>
                    {#if idx > 0}
                        <hr />
                    {/if}
                    <div class="flex flex-col gap-2">
                        <Label for={'speaker-' + idx} class="capitalize">
                            Speaker
                        </Label>
                        <Input
                            id={'speaker-' + idx}
                            type="text"
                            class="input block w-full"
                            bind:value={dialogue.speaker} />
                        <!-- test error messages here -->
                        <ErrorMessage message={$form?.errors?.dialogues} />
                    </div>
                    <div class="flex flex-col gap-2">
                        <Label for={'line-' + idx} class="capitalize">
                            Line
                        </Label>
                        <textarea
                            id={'line-' + idx}
                            class="textarea block w-full"
                            bind:value={dialogue.line}></textarea>
                        <!-- test error messages here -->
                        <ErrorMessage message={$form?.errors?.dialogues} />
                    </div>
                </div>
            {/each}
            <div class="flex justify-end">
                <Button class="item-right" onclick={addSpeaker}>
                    Add another speaker
                </Button>
            </div>
        </div>
        <div class="card flex flex-col gap-4">
            <h3>Metadata</h3>
            <Field {form} name="footnote" />
        </div>
        <div class="flex items-baseline justify-end">
            <Button>Create</Button>
        </div>
    </form>
</Page>
