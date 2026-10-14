import './bootstrap';
import '../css/app.css';

import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp, router } from '@inertiajs/react';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Khel Sathi';

// Global listener for Inertia flash & validation errors across all pages
router.on('navigate', (event) => {
    const pageProps = event.detail.page?.props;
    if (!pageProps) return;

    const flash = pageProps.flash;
    if (flash?.error) window.toastr.error(flash.error);
    if (flash?.success) window.toastr.success(flash.success);
    if (flash?.warning) window.toastr.warning(flash.warning);
    if (flash?.info) window.toastr.info(flash.info);

    const errors = pageProps.errors;
    if (errors && Object.keys(errors).length > 0) {
        window.toastr.error(Object.values(errors)[0]);
    }
});

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
        let page = pages[`./Pages/${name}.jsx`];
        if (!page) {
            page = pages[`./Pages/admin/${name}.jsx`];
        }
        if (!page) {
            throw new Error(`Page not found: ./Pages/${name}.jsx`);
        }
        return page.default ? page.default : page;
    },
    setup({ el, App, props }) {
        const initialProps = props.initialPage?.props;
        if (initialProps) {
            const flash = initialProps.flash;
            if (flash?.error) window.toastr.error(flash.error);
            if (flash?.success) window.toastr.success(flash.success);
            if (flash?.warning) window.toastr.warning(flash.warning);
            if (flash?.info) window.toastr.info(flash.info);

            const errors = initialProps.errors;
            if (errors && Object.keys(errors).length > 0) {
                window.toastr.error(Object.values(errors)[0]);
            }
        }

        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#0284c7',
    },
});
