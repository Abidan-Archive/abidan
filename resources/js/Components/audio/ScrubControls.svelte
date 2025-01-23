<script>
    import {
        Scissors,
        MagnifyingGlassPlus,
        MagnifyingGlassMinus,
    } from '@/Components/icons';
    import { IconButton } from '@/Components/forms';
    import MediaControls from '@/Components/audio/MediaControls.svelte';

    let { playpause, seek, clip, zoom } = $props();
</script>

<div class="flex w-full justify-between">
    <div class="w-1/6"></div>
    <MediaControls {playpause} {seek} {zoom} />
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
