<script>
    import cn from '@/lib/cn';
    import { cva } from 'class-variance-authority';

    /**
     * @typedef {Object} Props
     * @property {string} [class]
     * @property {any} [href]
     * @property {string} [type]
     * @property {string} [variant]
     * @property {string} [size]
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props & { [key: string]: any }} */
    let {
        class: className = '',
        href = undefined,
        type = 'submit',
        variant = 'default',
        size = 'default',
        children,
        ...rest
    } = $props();

    const buttonVariants = cva(
        'no-underline inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background ',
        {
            variants: {
                variant: {
                    default:
                        'bg-gray-200 text-gray-800 hover:bg-gray-200/90 border border-transparent hover:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 active:bg-gray-300',
                    success:
                        'bg-green-500 hover:bg-green-600 focus:bg-green-400',
                    danger: 'bg-red-600 hover:bg-red-500 focus:bg-red-300 text-typo-500',
                    warn: 'bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-400',
                    link: 'underline-offset-4 hover:underline',
                },
                size: {
                    default: 'h-10 py-2 px-4 duration-150 ease-in-out',
                    sm: 'h-9 px-3 rounded-md',
                    lg: 'h-11 px-9 rounded-md',
                },
            },
            defaultVariants: {
                variant: 'default',
                size: 'default',
            },
        }
    );
</script>

<svelte:element
    this={href ? 'a' : 'button'}
    role={href ? 'link' : 'button'}
    type={href ? undefined : type}
    {href}
    class={cn(buttonVariants({ variant, size, className }))}
    {...rest}>
    {@render children?.()}
</svelte:element>
