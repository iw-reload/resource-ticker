<?php

/*
 * @link https://github.com/iw-reload/resource-ticker
 * @copyright Copyright (c) 2016 iw-reload
 * @license https://raw.githubusercontent.com/iw-reload/resource-ticker/master/LICENSE
 */

namespace IwReload\ResourceTicker;

use IwReload\ResourceTicker\production\Dependency;

/**
 * @author Benjamin
 */
class Resource {

  private $id;
  
  public $stock = 0;
  public $productionPerTick = 0;
  public $storageLimit = PHP_INT_MAX;
  
  private $productionDependencies = [];
  
  public function __construct( $id ) {
    $this->id = $id;
  }

  public function addProductionDependency( $usedPerTick, Resource $resource ) {
    $this->productionDependencies[] = new Dependency( $resource, $usedPerTick );
  }
  
  public function getId() {
    return $this->id;
  }

}
