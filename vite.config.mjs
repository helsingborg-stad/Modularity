import { defineConfig } from 'vite'
import { resolve } from 'path'
const { manifestPlugin } = await import('vite-plugin-simple-manifest').then(m => m.default || m)

// Entry points configuration matching the original webpack config
const entries = {
  'js/modularity-editor-modal': './source/js/modularity-editor-modal.js',
  'js/modularity-text-module': './source/js/modularity-text-module.ts',
  'js/modularity': './source/js/modularity.js',
  'js/user-editable-list': './source/js/private/userEditableList.ts',
  'js/dynamic-map-acf': './source/js/admin/dynamic-map-acf.js',
  'js/block-validation': './source/js/block-validation.ts',
  'js/edit-modules-block-editor': './source/js/edit-modules-block-editor.ts',
  'js/mod-curator-load-more': './source/php/Module/Curator/assets/mod-curator-load-more.js',
  'js/video': './source/php/Module/Video/assets/video.js',
  'js/ungapd': './source/php/Module/Subscribe/assets/ungapd.ts',
  'js/mod-posts-taxonomy-filtering': './source/php/Module/Posts/assets/taxonomyFiltering.js',
  'js/mod-interactive-map': './source/php/Module/InteractiveMap/assets/interactiveMap.ts',
  
  'css/modularity': './source/sass/modularity.scss',
  'css/modularity-thickbox-edit': './source/sass/modularity-thickbox-edit.scss',
  'css/table': './source/php/Module/Table/assets/table.scss',
  'css/video': './source/php/Module/Video/assets/video.scss',
  'css/menu': './source/php/Module/Menu/assets/menu.scss',
  'css/interactive-map': './source/php/Module/InteractiveMap/assets/interactive-map.scss',
}

export default defineConfig(({ mode }) => {
  const isProduction = mode === 'production'
  return {
    build: {
      outDir: 'dist',
      emptyOutDir: true,
      rollupOptions: {
        input: entries,
        external: ['@helsingborg-stad/openstreetmap'], // Temporarily external until dependency is available
        output: {
          entryFileNames: isProduction ? '[name].[hash].js' : '[name].js',
          chunkFileNames: isProduction ? '[name].[hash].js' : '[name].js',
          assetFileNames: (assetInfo) => {
            if (assetInfo.name?.endsWith('.css')) {
              return isProduction ? '[name].[hash].css' : '[name].css'
            }
            return 'assets/[name].[hash].[ext]'
          }
        }
      },
      // Configure esbuild to handle TypeScript decorators properly
      minify: isProduction ? 'esbuild' : false,
      sourcemap: true
    },
    esbuild: {
      // Configure esbuild to prevent variable name collisions
      keepNames: true,
      minifyIdentifiers: false
    },
    css: {
      preprocessorOptions: {
        scss: {
          api: 'modern-compiler',
          includePaths: ['node_modules', 'source'],
          importers: [
            {
              findFileUrl(url) {
                if (url.startsWith('~')) {
                  return new URL(url.slice(1), new URL('../node_modules/', import.meta.url))
                }
                return null
              }
            }
          ]
        }
      }
    },
    resolve: {
      extensions: ['.tsx', '.ts', '.js', '.scss', '.css'],
      alias: {
        '~': resolve(__dirname, 'node_modules')
      }
    },
    plugins: [manifestPlugin('manifest.json')]
  }
})