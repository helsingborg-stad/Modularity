<?php

namespace Modularity\Integrations\Component;

use \ComponentLibrary\Integrations\Image\ImageFocusResolverInterface;

class ImageFocusResolver implements ImageFocusResolverInterface {

  public function __construct(private string $key){}

  public function getFocusPoint(int $id): array {
    $imageField = get_field($this->key, $id);
    if($imageField && isset($imageField['left'], $imageField['top'])) {
      return [
        'left' => $imageField['left'] ?? '50',
        'top' => $imageField['top'] ?? '50'
      ];
    }
  }
};