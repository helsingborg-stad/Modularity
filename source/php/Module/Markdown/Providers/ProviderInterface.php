<?php

namespace Modularity\Module\Markdown\Providers;

Interface ProviderInterface
{
  public function isValidProviderUrl(string $url): bool;
  public function getExample(): string;
  public function getName(): string;
}