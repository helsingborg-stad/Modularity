<?php 

namespace Modularity\Module\Markdown\Providers;

use Modularity\Module\Markdown\Providers\ProviderInterface;

class Github implements ProviderInterface
{
    public function isValidProviderUrl(string $url): bool
    {
        $pattern = '/raw.githubusercontent.com\/.*\.md$/';
        if (!preg_match($pattern, $url)) {
            return false;
        }
        return true;
    }

    public function getExample(): string
    {
        return 'https://raw.githubusercontent.com/Modularity/Modularity/develop/README.md';
    }

    public function getName(): string
    {
        return 'Github';
    }
}