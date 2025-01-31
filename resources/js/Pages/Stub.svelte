<script>
    import { inertia } from '@inertiajs/svelte';

    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import route from '@/lib/route';
    import { secondsToDuration } from '@/lib/time';

    let { stubs } = $props();
</script>

<Page header="All Stubs">
    <div class="mx-auto flex flex-col gap-3">
        <p class="text-center">
            Stubs are audio snippets that need transcription.
        </p>
        {#if !stubs.data.length}
            <p class="text-center">
                Currently there is no work to be done! Woohoo!
            </p>
        {:else}
            <Paginator {...stubs} />
            {#each stubs.data as stub}
                <div class="card grid grid-cols-3 items-center">
                    <a
                        class="h3"
                        use:inertia
                        href={route('stub.transcribe', [
                            stub.source.event_id,
                            stub.source_id,
                            stub.id,
                        ])}>
                        {stub.prompt || 'No Prompt Given'}
                    </a>
                    <div class="flex justify-center text-center">
                        {#if stub.audio_url}
                            <audio controls>
                                <source
                                    src={stub.audio_url}
                                    type="audio/mpeg" />
                                Your browser does not support the audio element.
                            </audio>
                        {:else}
                            <p>
                                Audio is currently not available for this stub.
                            </p>
                        {/if}
                    </div>
                    <div class="text-right">
                        <p>
                            Event:
                            <strong>
                                <a
                                    use:inertia
                                    href={route(
                                        'event.show',
                                        stub.source.event
                                    )}>
                                    {stub.source.event.name}
                                </a>
                            </strong>
                        </p>
                        <p>
                            Created:
                            <strong>
                                {new Date(stub.created_at).toLocaleDateString(
                                    'en-US'
                                )}
                            </strong>
                        </p>
                        <p>
                            Duration:
                            <strong>
                                {secondsToDuration(stub.to - stub.from)}
                            </strong>
                        </p>
                    </div>
                </div>
            {/each}
            <Paginator {...stubs} />
        {/if}
    </div>
</Page>
