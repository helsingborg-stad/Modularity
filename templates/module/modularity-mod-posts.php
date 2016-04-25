<?php
    $posts = \Modularity\Module\Posts\Posts::getPosts($module);
    var_dump(count($posts));
?>
