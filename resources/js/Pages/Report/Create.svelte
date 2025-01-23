<script>
    import Page from '@/Components/Page.svelte';
    import { ErrorMessage, Label, Button, Input } from '@/Components/forms';

    // let { events } = $props();
    let dialogues = $state([
        {
            speaker: 'Speaker',
            line: '',
        },
        {
            speaker: 'Will Wight',
            line: '',
        },
    ]);
    function submit(e) {
        e.preventDefault();
        console.log({ dialogues });
    }
</script>

<Page header="Create Report">
    <form method="POST" onsubmit={submit}>
        {#each dialogues as dialogue, idx}
            <div class="card">
                <div>
                    <Label for={'speaker-' + idx}>Speaker</Label>
                    <Input
                        id={'speaker-' + idx}
                        type="text"
                        name="speaker[]"
                        bind:value={dialogue.speaker} />
                    <ErrorMessage message={'idk'} class="mt-2" />
                </div>
                <div>
                    <Label for={'line-' + idx}>Line</Label>
                    <textarea
                        id={'line-' + idx}
                        name="line[]"
                        bind:value={dialogue.line}></textarea>
                    <ErrorMessage message={'idk'} class="mt-2" />
                </div>
            </div>
        {/each}
        <div class="flex items-baseline justify-end">
            <Button>Create</Button>
        </div>
    </form>
</Page>
