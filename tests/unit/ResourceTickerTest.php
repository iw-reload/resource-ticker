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

  public function testAdvanceTick_simple()
  {
    $r0 = new Resource(0);
    $r0->setStock( 0 );
    $r0->productionPerTick = 1;
    
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
