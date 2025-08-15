import { defineConfig } from 'vite'
import { resolve } from 'path'
import { glob } from 'glob'
import fs from 'fs'
import path from 'path'

// Entry points configuration matching the original webpack config
const entries = {
  // JavaScript/TypeScript files
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
  
  // SCSS files
  'css/modularity': './source/sass/modularity.scss',
  'css/modularity-thickbox-edit': './source/sass/modularity-thickbox-edit.scss',
  'css/table': './source/php/Module/Table/assets/table.scss',
  'css/video': './source/php/Module/Video/assets/video.scss',
  'css/menu': './source/php/Module/Menu/assets/menu.scss',
  'css/interactive-map': './source/php/Module/InteractiveMap/assets/interactive-map.scss',
}

// Plugin to generate manifest.json for cache busting
function manifestPlugin() {
  return {
    name: 'manifest-plugin',
    generateBundle(options, bundle) {
      const manifest = {}
      
      // Map files based on their generated names and match to entries
      for (const [fileName, chunk] of Object.entries(bundle)) {
        if (chunk.type === 'asset' && fileName.endsWith('.css')) {
          // CSS files - extract the base name from the generated file
          const baseName = fileName.replace(/\.[a-zA-Z0-9_-]+\.css$/, '')
          const entryKey = baseName + '.css'
          manifest[entryKey] = fileName
        } else if (chunk.type === 'chunk' && fileName.endsWith('.js')) {
          // JS files - extract the base name from the generated file  
          const baseName = fileName.replace(/\.[a-zA-Z0-9_-]+\.js$/, '')
          const entryKey = baseName + '.js'
          // Only add JS files that start with js/ to avoid CSS-related JS chunks
          if (entryKey.startsWith('js/')) {
            manifest[entryKey] = fileName
          }
        }
      }
      
      this.emitFile({
        type: 'asset',
        fileName: 'manifest.json',
        source: JSON.stringify(manifest, null, 2)
      })
    }
  }
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
      postcss: {
        plugins: [
          require('autoprefixer')
        ]
      },
      preprocessorOptions: {
        scss: {
          // Allow importing from node_modules and source directories
          includePaths: ['node_modules', 'source'],
          // Handle ~ imports like webpack
          importer: [
            function(url) {
              if (url.startsWith('~')) {
                return { file: url.slice(1) }
              }
              return null
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
    plugins: [
      manifestPlugin()
    ],
    server: {
      host: true,
      port: 3000,
      proxy: process.env.BROWSER_SYNC_PROXY_URL ? {
        '/': {
          target: process.env.BROWSER_SYNC_PROXY_URL,
          changeOrigin: true
        }
      } : undefined
    }
  }
})