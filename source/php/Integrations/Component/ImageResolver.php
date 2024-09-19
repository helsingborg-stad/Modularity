<?php

namespace Modularity\Integrations\Component;

use \ComponentLibrary\Integrations\Image\ImageResolverInterface;

class ImageResoler implements ImageResolverInterface {
  public function getImageUrl(int $id, array $size): ?string {
    $image = wp_get_attachment_image_src($id, $size); 
    if($image !== false && isset($image[0]) && filter_var($image[0], FILTER_VALIDATE_URL)) {
      return $image[0]; 
    }
    return null; 
  }
};