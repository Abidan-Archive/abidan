<script>
    import Page from '@/Components/Page.svelte';
    import Report from '@/Components/Report.svelte';

    let { report } = $props();
</script>

<svelte:head>
    <title>Report {report.id} | Abidan Archive</title>
    <meta property="og:title" content={`#${report.id} | Abidan Archive`} />
    <meta property="og:type" content="article" />
    <meta property="og:url" content={report.permalink} />
    <meta property="og:site_name" content="Abidan Archive" />
    <meta property="og:description" content={report.dialogues[0].line} />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content={`#${report.id} | Abidan Archive`} />
    <meta name="twitter:description" content={report.dialogues[0].line} />
</svelte:head>

<Page header={`Report #${report.id}`}>
    <Report {report} />
</Page>
