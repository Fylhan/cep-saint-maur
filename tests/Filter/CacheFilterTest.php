<?php
require_once __DIR__ . '/../Base.php';

use Filter\CacheFilter;

class CacheFilterTest extends Base
{

    /**
     *
     * @var \Filter\CacheFilter
     */
    private $filter;

    /**
     *
     * @var \Pimple\Container
     */
    private $mycontainer;

    public function setUp()
    {
        parent::setUp();
        $cacheManager = $this->getMockBuilder('\Service\CacheManager')
            ->setConstructorArgs(array(
            $this->container
        ))
            ->setMethods(array(
            'isEnabled',
            'cache',
            'retrieve'
        ))
            ->getMock();
        $response = $this->getMockBuilder('\Core\Response')
            ->setConstructorArgs(array(
            $this->container
        ))
            ->setMethods(array(
            'setBody',
            'printOut'
        ))
            ->getMock();
        
        $this->mycontainer = clone $this->container;
        $this->mycontainer['cacheManager'] = $cacheManager;
        $this->mycontainer['response'] = $response;
        $this->filter = new CacheFilter($this->mycontainer);
    }

    public function testPreFilter()
    {
        $this->mycontainer['cacheManager']->method('isEnabled')->will($this->onConsecutiveCalls(true, false));
        $this->mycontainer['cacheManager']->expects($this->once())
            ->method('retrieve');
        $this->assertFalse($this->filter->preFilter());
        $this->assertTrue($this->filter->preFilter());
    }

    public function testPostFilter()
    {
        $this->mycontainer['cacheManager']->method('isEnabled')->will($this->onConsecutiveCalls(true, false));
        $this->mycontainer['cacheManager']->expects($this->once())
            ->method('cache');
        ob_start();
        $this->filter->postFilter();
        $this->filter->postFilter();
    }
}