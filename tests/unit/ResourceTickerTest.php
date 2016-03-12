<?php

/*
 * @link https://github.com/iw-reload/resource-ticker
 * @copyright Copyright (c) 2016 iw-reload
 * @license https://raw.githubusercontent.com/iw-reload/resource-ticker/master/LICENSE
 */

use IwReload\ResourceTicker\Resource;
use IwReload\ResourceTicker\ResourceTicker;

class ResourceTickerTest extends \Codeception\TestCase\Test
{
  use \Codeception\Specify;
  
  /**
   * @var \UnitTester
   */
  protected $tester;
  
  /**
   * @var ResourceTicker
   */
  private $ticker;

  protected function _before()
  {
    $this->ticker = new ResourceTicker();
  }

  protected function _after()
  {
  }

  public function testAdvanceTick_linear()
  {
    $r0 = new Resource(0);
    $r0->setStock( 0 );
    $r0->productionPerTick = 1;
    
    // --- linear production --------------------------
    $r0->setProductionFunction(function($x){
      return 100;
    });
    
    $r0->setStockFunction(function($x){
      return $r0->getProduction($x) * $x;
    });
    
    // --- logistic production --------------------------
    // (population)
    $r0->setProductionFunction(function($x){
      return; // Uhhh... 1. Ableitung der StockFunction
    });

    // @see "https://de.wikipedia.org/wiki/Populationsdynamik#Mathematische_Modellierung"
    $r0->setStockFunction(function($t)
    {
      $N0 = $this->getInitialStock();
      $K = $this->getCapacityLimit();
      $e = M_E;                           // Euler's number
      $r = $this->getMaximumGrowthRate(); // maximum growth rate of the population
      
      return 1.0 / (
        (1.0/$N0 - 1.0/$K) * pow($e,-1.0*$r*$t) + 1.0/$K
        );
    });
    
    // @see "https://de.wikipedia.org/wiki/Populationsdynamik"
    
    // TODO: population follows a logarithmic pattern
    //       y = 38102.13036 ln(x) - 26400.38423 for a default planet
    // @see "http://www.xuru.org/rt/LnR.asp"
    // @see "http://doku.icewars.de/index.php/FAQ#Wie_schnell_w.C3.A4chst_meine_Bev.C3.B6lkerung.3F"
    
    // $r0->setProduction( new LinearProduction(1) );
    
    // LinearProduction is a constant function y = 1
    // Maybe make setProduction accept a callback function (x)
    // --> could be function (x) { return 1; } for LinearProduction(1)
    // --> could be function (x) { return 38102.13036 ln(x) - 26400.38423; } for LogarithmicProduction
    
    // But we will probably need to detect when it becomes zero
    // So, an Interface might be better:
    // getNextZero() : x
    // calcY( x )
    //
    // wait... this is production. Not stock.
    
    // Is there a PHP library which can help us?
    
    $this->specify('resource stock should increase', function() use ($r0) {
      $this->ticker->addResource( $r0 );
      
      $this->ticker->advanceTicks(1);
      verify($r0->getStock())->same(1);
      
      $this->ticker->advanceTicks(2);
      verify($r0->getStock())->same(3);
    });
    
    $r0->setStock( PHP_INT_MAX );
    $this->specify('resource stock should not overflow', function() use ($r0) {
      $this->ticker->addResource( $r0 );
      
      $this->ticker->advanceTicks(1);
      verify($r0->getStock())->same(PHP_INT_MAX);
    });
  }
}
