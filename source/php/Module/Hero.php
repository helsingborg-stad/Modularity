<?php

namespace Modularity\Module;

class Hero extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'hero',
            __("Hero (slider)",'modularity-plugin'),
            __("Heroes (sliders)",'modularity-plugin'),
            __("Outputs multiple images or videos in a sliding apperance.",'modularity-plugin'),
            array()
        );
    }
}

//TÄNKTE MIG FLICKITY HÄR, MED OPTIONS FÖR DE OLIKA UTSEENDENA
//http://flickity.metafizzy.co/
