<?php
require_once __DIR__ . '/../Base.php';

use Model\News;

class NewsTest extends Base
{

    public function testGetById()
    {
        $yesterday = date('d/m/Y', time()-60*60*24);
        $twoDayBefore = date('d/m/Y', time()-60*60*24*2);
        $tomorow = date('d/m/Y', time()+60*60*24);
        $manyDaysAfter = date('d/m/Y', time()+60*60*24*10000);
        
        $u = new News($this->container);
        $this->assertEmpty($u->getById(1));
        $this->assertEmpty($u->getById(1, true));
        
        // Disabled
        $this->assertNotFalse($u->update(array(
            'title' => 'Test disabled',
            'content' => 'My content',
            'date_start' => $yesterday,
            'date_end' => $manyDaysAfter,
            'state' => 0
        )));
        $this->assertEmpty($u->getById(1));
        $this->assertNotEmpty($u->getById(1, true));
        
        // Enabled and ready
        $this->assertNotFalse($u->update(array(
            'id' => 1,
            'title' => 'Test disabled',
            'content' => 'My content',
            'date_start' => $yesterday,
            'date_end' => $manyDaysAfter,
            'state' => 1
        )));
        
        $this->assertNotEmpty($u->getById(1));
        $this->assertNotEmpty($u->getById(1, true));
        
        // Planned
        $this->assertNotFalse($u->update(array(
            'id' => 1,
            'title' => 'Test disabled',
            'content' => 'My content',
            'date_start' => $tomorow,
            'date_end' => $manyDaysAfter,
            'state' => 1
        )));

        // Finished
        $this->assertEmpty($u->getById(1));
        $this->assertNotEmpty($u->getById(1, true));
        
        $this->assertNotFalse($u->update(array(
            'id' => 1,
            'title' => 'Test disabled',
            'content' => 'My content',
            'date_start' => $twoDayBefore,
            'date_end' => $yesterday,
            'state' => 1
        )));
        
        $this->assertEmpty($u->getById(1));
        $this->assertNotEmpty($u->getById(1, true));
    }
}
