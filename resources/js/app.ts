import '../css/app.css';

import type { Page } from '@inertiajs/core';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { i18nVue, loadLanguageAsync } from 'laravel-vue-i18n';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';

import { initializeTheme } from './composables/useAppearance';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    async setup({ el, App, props, plugin }) {
        const locale =
            (props.initialPage.props.locale as string | undefined) ||
            document.documentElement.lang ||
            'en';
        const loaders = import.meta.glob('../../lang/*.json', { eager: true });
        const resolve = (lang: string) => {
            const key = `../../lang/${lang}.json`;
            const entry = loaders[key] as Record<string, unknown> | undefined;
            if (entry) {
                return 'default' in entry ? entry.default : entry;
            }

            const short = lang.split(/[-_]/)[0];
            const shortKey = `../../lang/${short}.json`;
            const shortEntry = loaders[shortKey] as Record<string, unknown> | undefined;
            if (shortEntry) {
                return 'default' in shortEntry ? shortEntry.default : shortEntry;
            }

            return {};
        };

        const syncLocale = (page: Page) => {
            const nextLocale =
                (page.props.locale as string | undefined) || locale;
            void loadLanguageAsync(nextLocale);
        };

        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18nVue, {
                lang: locale,
                fallbackLang: 'en',
                resolve,
            });

        await loadLanguageAsync(locale);

        app.mount(el);

        syncLocale(props.initialPage);
        router.on('success', (event) => {
            syncLocale(event.detail.page);
        });
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
