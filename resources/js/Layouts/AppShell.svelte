<script>
    /**
     * @typedef {Object} Props
     * @property {import('svelte').Snippet} [header]
     * @property {import('svelte').Snippet} [children]
     * @property {import('svelte').Snippet} [footer]
     */

    /** @type {Props & { [key: string]: any }} */
    let { header, children, footer, ...rest } = $props();
</script>

<div
    id="appShell"
    data-testid="app-shell"
    class="flex h-full w-full flex-col overflow-hidden">
    <header class="z-10 flex-none">
        {@render header?.()}
    </header>
    <div id="content-area" class="flex h-full w-full flex-auto overflow-hidden">
        <div
            id="page"
            class="flex flex-1 flex-col overflow-x-hidden"
            style:scrollbar-gutter="auto"
            {...rest}>
            <main id="page-content" class="flex-auto">
                {@render children?.()}
            </main>
            <footer id="page-footer" class="flex-none">
                {@render footer?.()}
            </footer>
        </div>
    </div>
</div>
