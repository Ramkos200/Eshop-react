import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import php from 'vite-plugin-php'
// https://vitejs.dev/config/
export default defineConfig({
plugins: [react(), php()],
build: {
outDir: '../public',
emptyOutDir: true, // This will empty the Laravel public/ dir on each build
// Generate manifest.json for Laravel to handle cache busting
manifest: true,
},
// Point the dev server to the Laravel public directory
publicDir: '../public',
})