import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        VitePWA({
            registerType: 'autoUpdate',
            manifest: {
                name: 'FaunaFond',
                short_name: 'FaunaFond',
                start_url: '/',
                display: 'standalone',
                background_color: '#ffffff',
                theme_color: '#2f855a',
            }
        })
    ],
})
