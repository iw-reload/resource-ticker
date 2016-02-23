<?php

/*
 * @link https://github.com/iw-reload/resource-ticker
 * @copyright Copyright (c) 2016 iw-reload
 * @license https://raw.githubusercontent.com/iw-reload/resource-ticker/master/LICENSE
 */

namespace IwReload\ResourceTicker;

/**
 * @author Benjamin
 */
class ResourceTicker {
  
  /**
   * @var Resource[]
   */
  private $resources = [];
  
  public function addResource( Resource $r ) {
    $this->resources[] = $r;
  }
  
  public function advanceTicks( $nTicks ) {
    foreach($this->resources as $r) {
      $r->modifyStock( $r->productionPerTick * $nTicks );
    }
  }
}
