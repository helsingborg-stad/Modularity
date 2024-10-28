export const ModulesRestAPIEndpoints = (basePath) => ({
    getModule: `${basePath}modularity/v1/modules`,
})

export class PaginationTransition {

    scrollPosition = null;
    sessionStorageKey = 'scrollPosition';

    setupEventListeners() {
        window.addEventListener('load', () => {
            if (this.getSavedScrollPosition() > 0) {
                window.scrollTo(0, this.getSavedScrollPosition());
                sessionStorage.removeItem(this.sessionStorageKey);
            }
        });

        this.setupNodeEventListeners();
    }

    setupNodeEventListeners() {
        const nodes = document.querySelectorAll(this.getSelectors());

        nodes.forEach(node => {
            node.addEventListener('click', (event) => {
                this.saveScrollPosition();
                document.startViewTransition(() => {
                    const href = event.currentTarget.href;
                    setTimeout(() => {
                        window.location.href = href;
                    }, 1000);
                });
            });
        });
    }

    getSelectors() {
        return ".modularity-mod-posts .c-pagination__item a";
    }

    getModuleIdFromUrl(url) {
        return new URLSearchParams(url).get(this.trigger);
    }

    saveScrollPosition() {
        const scrollPosition = window.scrollY || document.documentElement.scrollTop;
        sessionStorage.setItem(this.sessionStorageKey, scrollPosition);
    }

    getSavedScrollPosition() {
        if (this.scrollPosition === null) {
            this.scrollPosition = sessionStorage.getItem(this.sessionStorageKey) || 0;
        }

        return this.scrollPosition;
    }
}