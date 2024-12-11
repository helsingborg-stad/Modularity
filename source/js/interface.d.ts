interface Window {
    wpApiSettings: {
        root: string;
        nonce: string;
    };
}

interface WpObject {
    autosave?: {
        server: {
            handleUnload: () => void;
            triggerSave: () => void;
        };
    };
}

declare const wp: WpObject;

interface CustomWindow extends Window {
    Modularity: {
        Editor?: {
            Autosave?: {},
            DragAndDrop?: {},
            Module?: {},
            Thickbox?: {
                postAction?: string;
            },
            Validate?: {}
        },
        Helpers?: {
            Helpers?: {},
            Widget?: {}
        },
        Prompt?: {
            Modal?: {
                open: (url: string) => void;
            }
        }
    }
}

