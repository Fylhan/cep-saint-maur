<?php
require_once __DIR__ . '/../Base.php';

use Service\CacheManager;

class CacheManagerTest extends Base
{

    private $cacher;

    private $mycontainer;

    public function setUp()
    {
        parent::setUp();
        $request = $this->getMockBuilder('\Core\Request')
            ->setConstructorArgs(array(
            $this->container
        ))
            ->setMethods(array(
            'isPost',
            'getController',
            'getAction',
            'getId'
        ))
            ->getMock();
        $request->method('getAction')->will($this->returnValue('index'));
        
        $this->mycontainer = clone $this->container;
        $this->mycontainer['request'] = $request;
        $this->cacher = new CacheManager($this->mycontainer);
    }

    public function testGetController()
    {
        $this->mycontainer['request']->method('getController')->will($this->returnValue('content'));
        $this->assertEquals(CACHE_PATH . '/othercontroller', $this->cacher->getCacheDir('othercontroller'));
        $this->assertEquals(CACHE_PATH . '/content', $this->cacher->getCacheDir());
    }

    public function testGetCacheFilepath()
    {
        $this->mycontainer['request']->method('getController')->will($this->returnValue('content'));
        $this->mycontainer['request']->method('getId')->will($this->returnValue('test'));
        $this->assertEquals(CACHE_PATH . '/content/test.cache', $this->cacher->getCacheFilepath());
    }

    public function testIsEnabled()
    {
        $this->mycontainer['request']->expects($this->any())
            ->method('isPost')
            ->will($this->onConsecutiveCalls(false, true, false, false, true));
        $this->mycontainer['request']->expects($this->any())
            ->method('getController')
            ->will($this->onConsecutiveCalls('content', 'admin', 'upload'));
        
        $this->assertEquals(true, $this->cacher->isEnabled());
        $this->assertEquals(false, $this->cacher->isEnabled());
        $this->assertEquals(false, $this->cacher->isEnabled());
        $this->assertEquals(false, $this->cacher->isEnabled());
        $this->assertEquals(false, $this->cacher->isEnabled());
    }
}