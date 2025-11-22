import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    emptyOutDir: false,
    rollupOptions: {
      input: ['resources/css/tree.css'],
      output: {
        assetFileNames: (assetInfo) => {
          if (assetInfo.name === 'tree.css') {
            return 'tree.css';
          }
          return assetInfo.name;
        }
      }
    },
    outDir: 'public',
  },
});