<script>
    import { page } from '@inertiajs/svelte';
    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import Report from '@/Components/Report.svelte';
    import route from '@/lib/route';

    let { tag, reports } = $props();

    const edit = $derived(
        ['admin', 'moderator'].some((role) =>
            $page.props.auth.user?.roles.includes(role)
        ) && route('tag.edit', tag)
    );
</script>

<Page header={`Tag - ${tag.name} (${reports.total})`} {edit}>
    <div class="flex flex-col gap-5">
        {#if !reports.data.length}
            Yikes, this tag has no reports.
        {:else}
            <Paginator {...reports} />
            {#each reports.data as report}
                <Report {report} />
            {/each}
            <Paginator {...reports} />
        {/if}
    </div>
</Page>
