import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        host: "0.0.0.0",
        hmr: {
            // TODO: Change into ip public
            host: "0.0.0.0",
            protocol: "http",
        },
        watch: {
            usePolling: true,
        },
    },
});
