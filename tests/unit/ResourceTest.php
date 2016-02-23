<?php

/*
 * @link https://github.com/iw-reload/resource-ticker
 * @copyright Copyright (c) 2016 iw-reload
 * @license https://raw.githubusercontent.com/iw-reload/resource-ticker/master/LICENSE
 */

use IwReload\ResourceTicker\Resource;

class ResourceTest extends \Codeception\TestCase\Test
{
  use \Codeception\Specify;
  
  /**
   * @var \UnitTester
   */
  protected $tester;

  protected function _before()
  {
  }

  protected function _after()
  {
  }

  public function testCreation()
  {
    $this->specify('can create', function() {
      $resource = new Resource(0);
      verify($resource)->isInstanceOf(Resource::class);
    });
    
    $this->specify('id does not change', function() {
      $resource1 = new Resource(0);
      verify($resource1->getId())->same(0);

      $resource2 = new Resource('test');
      verify($resource2->getId())->same('test');
    });
  }
  
  public function testSetStock()
  {
    $this->specify('stock is properly set', function($stock) {
      $r0 = new Resource(0);
      $r0->setStock($stock);
      verify($r0->getStock())->same($stock);
    }, [
      'examples' => [
        [0],
        [PHP_INT_MAX],
      ],
    ]);
    
    $this->specify('stock must be int', function($invalidStock) {
      $r0 = new Resource(0);
      $r0->setStock($invalidStock);
    }, [
      'examples' => [
        [null],
        [''],
        [1.2],
      ],
      'throws' => new \InvalidArgumentException,
    ]);
    
    $this->specify('negative stock results in exception', function() {
      $r0 = new Resource(0);
      $r0->setStock(-1);
    }, ['throws' => new \InvalidArgumentException,]);
  }

  public function testModifyStock()
  {
    $this->specify('diff must be int', function($invalidDiff) {
      $r0 = new Resource(0);
      $r0->modifyStock($invalidDiff);
    }, [
      'examples' => [
        [null],
        [''],
        [1.2],
      ],
      'throws' => new \InvalidArgumentException,
    ]);
    
    $this->specify('stock must not get smaller than zero', function() {
      $r0 = new Resource(0);
      $r0->setStock(0);
      $r0->modifyStock(-1);
    }, ['throws' => new \RangeException,]);
    
    $this->specify('stock must not get bigger than storage limit', function() {
      $r0 = new Resource(0);
      $r0->storageLimit = 1;
      $r0->setStock(0);
      $r0->modifyStock(2);
      verify($r0->getStock())->same(1);
    });
    
    $this->specify('stock must not overflow', function() {
      $r0 = new Resource(0);
      $r0->setStock(PHP_INT_MAX);
      $r0->modifyStock(1);
      verify($r0->getStock())->same(PHP_INT_MAX);
    });
    
    $this->specify('stock is properly modified', function($diff,$expected) {
      $r = new Resource(0);
      $r->setStock(5);
      $r->modifyStock($diff);
      verify($r->getStock())->same($expected);
    }, [
      'examples' => [
        [-5, 0],
        [0, 5],
        [5, 10],
      ],
    ]);
  }
  
}