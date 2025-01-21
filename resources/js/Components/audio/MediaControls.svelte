<script>
    import {
        PlayPause,
        Previous,
        Next,
        Forward,
        Backward,
        Scissors,
        MagnifyingGlassPlus,
        MagnifyingGlassMinus,
    } from '@/Components/icons';
    import { IconButton } from '@/Components/forms';

    let { playpause, seek, clip, zoom } = $props();

    const controls = [
        {
            title: 'seek backward 5 seconds',
            event: () => seek(-5),
            icon: Backward,
        },
        {
            title: 'seek backward 1 seconds',
            event: () => seek(-1),
            icon: Previous,
        },
        {
            title: 'toggle play pause',
            event: () => playpause(),
            icon: PlayPause,
            variant: { large: true },
        },
        {
            title: 'seek forward 1 seconds',
            event: () => seek(1),
            icon: Next,
        },
        {
            title: 'seek forward 5 seconds',
            event: () => seek(5),
            icon: Forward,
        },
    ];
</script>

<div class="flex w-full justify-between">
    <div class="w-1/6"></div>
    <div class="align-center flex justify-center gap-2">
        {#each controls as control}
            <IconButton
                title={control.title}
                onclick={control.event}
                {...control.variant}>
                <control.icon />
            </IconButton>
        {/each}
    </div>
    <div class="w-1/6 self-center justify-self-end text-right">
        <button
            title="zoom out from waveform"
            class="hover:text-typo-600"
            onclick={() => zoom(false)}>
            <MagnifyingGlassMinus />
        </button>
        <button
            title="zoom into waveform"
            class="hover:text-typo-600"
            onclick={() => zoom(true)}>
            <MagnifyingGlassPlus />
        </button>
    </div>
</div>
<div class="align-center flex justify-center gap-2">
    <IconButton title="create a segment" onclick={() => clip()} large>
        <Scissors />
    </IconButton>
</div>
