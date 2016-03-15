<?php
require_once __DIR__ . '/../Base.php';

use Model\News;

class NewsTest extends Base
{

    public function testGetById()
    {
        $now = time();
        $yesterdayTimestamp = $now-60*60*24;
        $yesterday = date('d/m/Y', $yesterdayTimestamp);
        $twoDayBefore = date('d/m/Y', time()-60*60*24*2);
        $tomorowTimestamp = $now+60*60*24;
        $tomorow = date('d/m/Y', $tomorowTimestamp);
        $manyDaysAfterTimestamp = $now+60*60*24*10000;
        $manyDaysAfter = date('d/m/Y', $manyDaysAfterTimestamp);
        
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
        $news = $u->getById(1, true);
        $this->assertEquals($news['id'], 1);
        $this->assertEquals($news['title'], 'Test disabled');
        $this->assertEquals($news['date_start'], $yesterdayTimestamp);
        $this->assertEquals($news['date_end'], $manyDaysAfterTimestamp);
        $this->assertEquals($news['state'], 0);
        
        // Enabled and ready
        $this->assertNotFalse($u->update(array(
            'id' => 1,
            'title' => 'Test enabled',
            'date_start' => $yesterday,
            'date_end' => $manyDaysAfter,
            'state' => 1
        )));
        
        $this->assertNotEmpty($u->getById(1));
        $this->assertNotEmpty($u->getById(1, true));
        $news = $u->getById(1);
        $this->assertEquals($news['id'], 1);
        $this->assertEquals($news['title'], 'Test enabled');
        $this->assertEquals($news['content'], 'My content');
        $this->assertEquals($news['date_start'], $yesterdayTimestamp);
        $this->assertEquals($news['date_end'], $manyDaysAfterTimestamp);
        $this->assertEquals($news['state'], 1);
        
        // Planned
        $this->assertNotFalse($u->update(array(
            'id' => 1,
            'date_start' => $tomorow,
            'date_end' => $manyDaysAfter,
            'state' => 1
        )));

        // Finished
        $this->assertEmpty($u->getById(1));
        $this->assertNotEmpty($u->getById(1, true));
        $news = $u->getById(1, true);
        $this->assertEquals($news['id'], 1);
        $this->assertEquals($news['title'], 'Test enabled');
        $this->assertEquals($news['content'], 'My content');
        $this->assertEquals($news['date_start'], $tomorowTimestamp);
        $this->assertEquals($news['date_end'], $manyDaysAfterTimestamp);
        $this->assertEquals($news['state'], 1);
        
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
    
    public function testGetAll()
    {
        $yesterday = date('d/m/Y', time()-60*60*24);
        $manyDaysAfter = date('d/m/Y', time()+60*60*24*10000);
    
        $u = new News($this->container);
        $this->assertEmpty($u->getAll(0, 2));
        $this->assertEmpty($u->getAll(0, 2, true));
    
        // Disabled
        $this->assertNotFalse($u->update(array(
            'title' => 'Test disabled',
            'content' => 'My content',
            'date_start' => $yesterday,
            'date_end' => $manyDaysAfter,
            'state' => 0
        )));
        $this->assertNotFalse($u->update(array(
            'title' => 'Test disabled 2',
            'content' => 'My content 2',
            'date_start' => $yesterday,
            'date_end' => $manyDaysAfter,
            'state' => 0
        )));
        $this->assertEmpty($u->getAll(0, 2));
        $this->assertEquals(count($u->getAll(0, 2, true)), 2);
    
        // Enabled and ready
        $this->assertNotFalse($u->update(array(
            'id' => 1,
            'title' => 'Test enabled 1',
            'content' => 'My content 1',
            'date_start' => $yesterday,
            'date_end' => $manyDaysAfter,
            'state' => 1
        )));
        $this->assertNotFalse($u->update(array(
            'title' => 'Test disabled 3',
            'content' => 'My content 3',
            'date_start' => $yesterday,
            'date_end' => $manyDaysAfter,
            'state' => 0
        )));
    
        $this->assertEquals(count($u->getAll(0, 2)), 1);
        $this->assertEquals(count($u->getAll(0, 2, true)), 2);
        $this->assertEquals(count($u->getAll(0, 3, true)), 3);
    }
}
