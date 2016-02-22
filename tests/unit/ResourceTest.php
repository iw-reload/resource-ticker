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
}