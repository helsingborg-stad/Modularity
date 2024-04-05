import ModuleFilteringSetup from "./moduleFilteringSetup";

document.addEventListener('DOMContentLoaded', () => {
    if (pagenow === 'mod-posts') {
        new ModuleFilteringSetup(modPosts.currentPostID);
    }
});