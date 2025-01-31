<script>
    import AdminSidebar from '@/Pages/Admin/components/AdminSidebar.svelte';
    import Page from '@/Components/Page.svelte';
    import Paginator from '@/Components/Paginator.svelte';
    import ReviewReportForm from '@/Components/ReviewReportForm.svelte';

    let { reports } = $props();
</script>

<div class="mt-8 grid h-full grid-cols-7 gap-4 px-4">
    <AdminSidebar />
    <Page header="Review Reports" class="col-span-5 flex flex-col gap-4">
        {#if !reports.data.length}
            <div class="text-center">
                <p>There are no more reports to review! Great job team!</p>
                <p>Now go ban someone to celebrate!</p>
            </div>
        {:else}
            <h4 class="h4 text-center">
                There are {reports.total} reports awaiting review.
            </h4>
            {#each reports.data as report, idx}
                {#if idx > 0}
                    <hr />
                {/if}
                <ReviewReportForm {report} />
            {/each}
            <Paginator {...reports} />
        {/if}
    </Page>
</div>
