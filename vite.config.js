import { defineConfig, loadEnv } from "vite";
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd(), "") };

    return {
        // server: {
        //     hmr: {
        //         host: process.env.HOST,
        //     },
        // },
        build: {
            outDir: "./public/dist",
            emptyOutDir: true,
        },
        plugins: [
            laravel({
                input: ["resources/css/app.css"],
                refresh: true,
            }),
        ],
    };
});
