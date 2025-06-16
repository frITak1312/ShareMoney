import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/authenticate.css",
                "resources/css/accountPage.css",
                "resources/js/editProfilePhoto.js",
                "resources/js/accountDetail.js",
                "resources/js/dashboard.js",
                "resources/js/memberManagement.js",
            ],
            refresh: true,
        }),
    ],
});
