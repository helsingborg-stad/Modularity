<?php

namespace Modularity;

abstract class Module
{
    public static $slug = '';
    public static $nameSingular = '';
    public static $namePlural = '';
    public static $description = '';
    public static $icon = '';
    public static $supports = array();
    public static $plugin = array();
    public static $cacheTtl = 0;
    public static $hideTitle  = false;
}
