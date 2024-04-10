declare const wp: any;

interface Block {
    name: string;
    attributes: {
        mode: string;
        data: Object;
        // other attributes
    };
    clientId: string;
    // other properties
}


class BlockFilteringSetup {
    postsBlockBlocks: Array<Block> = [];

    declare wp: any;

    constructor(private postId: String) {
        this.initialize();
    }

    private async initialize() {
        this.postsBlockBlocks = await this.getBlocks();

        
    }

    private getBlocks(): Promise<Block[]> {
        return new Promise((resolve) => {
            if (!wp || !wp.data) {
                resolve([]);
            }

            let i = 0;
            const maxIterations = 30; // sets a maximum amount of iterations

            const intervalId = setInterval(() => {
                // i++;
                const blocks = wp.data.select('core/block-editor').getBlocks();
                if (blocks) {
                    clearInterval(intervalId);
                    resolve(this.getPostsBlocks(blocks));
                }
            }, 300);
        });
    }

    private getPostsBlocks(blocks: Block[] = []): Block[] {
        let postsBlocks = [];
        for (const [key, block] of Object.entries(blocks)) {
            if (block.name !== 'acf/posts') {
                continue;
            }

            const blockId = block.clientId;
            const blockData = block.attributes?.data;


        }   
        return postsBlocks;
    }
}

export default BlockFilteringSetup;