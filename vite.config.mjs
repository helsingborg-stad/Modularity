import { createViteConfig } from 'vite-config-factory'

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

export default createViteConfig(entries, {
  outDir: 'dist',
  manifestFile: 'manifest.json'
})