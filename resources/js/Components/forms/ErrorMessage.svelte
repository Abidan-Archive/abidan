<script>
    let { ...props } = $props();
</script>

{#if !!props.message}
    <ul class={`space-y-1 text-sm text-red-400 ${props.class}`}>
        <li>{props.message}</li>
    </ul>
{/if}
