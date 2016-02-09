<?php

namespace Modularity;

class Images
{
    public function __construct()
    {
        add_action('init', array($this,'registerImageSizes'));
    }

    public function registerImageSizes()
    {

        //Pattern: width, height, crop

        $imageSizes = array(
            'xxxs'  => array('300',300,true),
            'xxs'   => array('300',300,true),
            'xs'    => array('300',300,true),
            's'     => array('300',300,true),
            'm'     => array('300',300,true),
            'l'     => array('300',300,true),
            'xl'    => array('300',300,true),
            'xxl'   => array('300',300,true),
            'xxl'   => array('300',300,true)
        );


    }
}
