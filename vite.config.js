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
      
      // Track CSS files separately
      const cssFiles = {}
      const jsFiles = {}
      
      for (const [fileName, chunk] of Object.entries(bundle)) {
        if (chunk.type === 'asset' && fileName.endsWith('.css')) {
          // CSS files - map based on the source entry
          for (const [entryName, entryPath] of Object.entries(entries)) {
            if (entryName.startsWith('css/') && chunk.facadeModuleId?.includes(entryPath.replace('./', ''))) {
              cssFiles[entryName + '.css'] = fileName
              break
            }
          }
        } else if (chunk.type === 'chunk' && fileName.endsWith('.js')) {
          // JS files
          for (const [entryName, entryPath] of Object.entries(entries)) {
            if (entryName.startsWith('js/') && (
              chunk.facadeModuleId?.includes(entryPath.replace('./', '')) ||
              chunk.name === entryName ||
              entryPath.includes(chunk.name)
            )) {
              jsFiles[entryName + '.js'] = fileName
              break
            }
          }
        }
      }
      
      // Manual mapping for CSS files based on known patterns
      for (const fileName of Object.keys(bundle)) {
        if (fileName.endsWith('.css')) {
          const baseName = fileName.replace(/\.[a-zA-Z0-9_-]+\.css$/, '').replace('.css', '')
          if (baseName === 'css/modularity') cssFiles['css/modularity.css'] = fileName
          if (baseName === 'css/modularity-thickbox-edit') cssFiles['css/modularity-thickbox-edit.css'] = fileName
          if (baseName === 'css/table') cssFiles['css/table.css'] = fileName
          if (baseName === 'css/video') cssFiles['css/video.css'] = fileName
          if (baseName === 'css/menu') cssFiles['css/menu.css'] = fileName
          if (baseName === 'css/interactive-map') cssFiles['css/interactive-map.css'] = fileName
        }
      }
      
      Object.assign(manifest, cssFiles, jsFiles)
      
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
      sourcemap: true
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