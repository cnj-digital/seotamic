import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue2";
import inject from "@rollup/plugin-inject";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/js/cp.js", "resources/css/cp.css"],
      publicDirectory: "resources/dist",
    }),
    vue(),
    inject({
      Vue: "vue",
      include: "resources/js/**",
    }),
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm.js",
    },
  },
});
