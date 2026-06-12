import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2'; // এই লাইনটি মিসিং ছিল

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(), // এই প্লাগইন কলটি মিসিং ছিল
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm.js',
        },
    },
});
