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
  
  /**
   * @var int
   */
  private $stock = 0;
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

  /**
   * @return int
   */
  public function getStock() {
    return $this->stock;
  }

  /**
   * @param int $value >= 0
   * @throws \InvalidArgumentException
   */
  public function setStock( $value )
  {
    if (!is_int($value)) {
      throw new \InvalidArgumentException(__METHOD__." only accepts integers. Input was: '{$value}'" );
    }
    
    if ($value < 0) {
      throw new \InvalidArgumentException( __METHOD__." only accepts positive values. Input was: '{$value}'" );
    }
    
    $this->stock = $value;
  }
  
  /**
   * @param int $diff
   * @throws \InvalidArgumentException
   */
  public function modifyStock( $diff )
  {
    if (!is_int($diff)) {
      throw new \InvalidArgumentException(__METHOD__." only accepts integers. Input was: '{$diff}'" );
    }
    
    if ($diff < 0 && ($this->stock + $diff) < 0) {
      throw new \RangeException(__METHOD__." can not change stock to value less than zero. Stock was '{$this->stock}', input was: '{$diff}'");
    }
    
    $this->stock = $this->storageLimit - $diff < $this->stock
      ? $this->storageLimit
      : $this->stock + $diff;
  }
}
