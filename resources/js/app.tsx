import './bootstrap';
import '../css/app.css';

import {createRoot} from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/react';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {Provider} from "react-redux";
import {createStore} from "redux";
import appStore from "@/store/store";

const appName = 'UtilityBox';
const store = createStore(appStore);

createInertiaApp({
    title: (title: string) => `${title} - ${appName}`,
    resolve: (name: any) =>
        resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')) as any,
    setup({el, App, props}) {
        const root = createRoot(el);
        // Wrap the App component with the Layout component
        root.render(<Provider store={store}><App {...props} /></Provider>);
    },
    progress: {
        color: '#d67f1f',
    },
});
