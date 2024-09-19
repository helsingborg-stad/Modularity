<?php

namespace Modularity\Integrations\Component;

use \ComponentLibrary\Integrations\Image\ImageFocusResolverInterface;

class ImageFocusResolver implements ImageFocusResolverInterface {

  public function __construct(private $data){}

  public function getFocusPoint(): array {
    $data = $this->data;
    if($data && isset($data['left'], $data['top'])) {
      return [
        'left' => $data['left'] ?? 50,
        'top' => $data['top'] ?? 50
      ];
    }
    return ['left' => 50, 'top' => 50];
  } 
}
