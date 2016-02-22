<?php

/*
 * @link https://github.com/iw-reload/resource-ticker
 * @copyright Copyright (c) 2016 iw-reload
 * @license https://raw.githubusercontent.com/iw-reload/resource-ticker/master/LICENSE
 */

namespace IwReload\ResourceTicker\production;

use IwReload\ResourceTicker\Resource;

/**
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class Dependency {
  
  private $resource = null;
  private $usedPerTick = 0;
  
  public function __construct( Resource $resource, $usedPerTick ) {
    $this->resource = $resource;
    $this->usedPerTick = $usedPerTick;
  }
  
}
