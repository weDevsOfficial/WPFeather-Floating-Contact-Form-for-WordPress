import React from 'react';
import path from 'path';
import { defineConfig } from "vite";
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig(() => ({
    publicDir: "resources",
    build: {
        assetsDir: "",
        emptyOutDir: true,
        outDir: path.resolve(__dirname, 'assets' ),
        rollupOptions: {
            input: path.resolve(__dirname, 'src/js/index.jsx' ),
            output: {
                // Output CSS files in a 'css' directory
                // Output image files in an 'img' directory
                assetFileNames: (assetInfo) => {
                    const extType = assetInfo.name.split('.').pop();
                    let directory = '';

                    if (extType === 'css') {
                        directory = 'css/';
                    }

                    return `${directory}[name][extname]`;
                },

                // Output JS files in a 'js' directory
                chunkFileNames: 'js/[name].js',
                entryFileNames: 'js/[name].js'
            }
        },
    },
    plugins: [
        {
            name: "php",
            handleHotUpdate({ file, server }) {
                if (file.endsWith(".php")) {
                    server.ws.send({ type: "full-reload", path: "*" });
                }
            },
        },
        viteStaticCopy({
            targets: [
                {
                    src: 'src/js/floating-form.js',
                    dest: 'js/'
                }
            ]
        })
    ],
}));
