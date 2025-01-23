<script>
    import {
        PlayPause,
        Previous,
        Next,
        Forward,
        Backward,
    } from '@/Components/icons';
    import { IconButton } from '@/Components/forms';
    import cn from '@/lib/cn';

    let { playpause, seek, class: className } = $props();

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

<div class={cn('flex justify-center gap-2 text-center', className)}>
    {#each controls as control}
        <IconButton
            title={control.title}
            onclick={control.event}
            {...control.variant}>
            <control.icon />
        </IconButton>
    {/each}
</div>
