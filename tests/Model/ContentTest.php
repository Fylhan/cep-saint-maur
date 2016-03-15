<?php
require_once __DIR__ . '/../Base.php';

use Model\Content;

class ContentTest extends Base
{

    public function testGetByUrl()
    {
        $u = new Content($this->container);
        $this->assertEmpty($u->getByUrl('test'));
        
        $this->assertNotFalse($u->update(array(
            'url' => 'test',
            'content' => 'My content',
            'title' => 'My test',
            'abstract' => 'content1',
            'keywords' => 'content2'
        )));
        $this->assertEmpty($u->getByUrl('bad test'));
        $test = $u->getByUrl('test');
        $this->assertNotEmpty($test);
        $this->assertEquals($test['id'], 5);
        $this->assertEquals($test['content'], 'My content');
        $this->assertEquals($test['title'], 'My test');
        $this->assertEquals($test['abstract'], 'content1');
        $this->assertEquals($test['keywords'], 'content2');
        
        // Enabled and ready
        $this->assertNotFalse($u->update(array(
            'id' => 5,
            'content' => 'My content updated',
            'title' => 'My test updated'
        )));
        
        $this->assertEmpty($u->getByUrl('bad test'));
        $test = $u->getByUrl('test');
        $this->assertNotEmpty($test);
        $this->assertEquals($test['id'], 5);
        $this->assertEquals($test['content'], 'My content updated');
        $this->assertEquals($test['title'], 'My test updated');
        $this->assertEquals($test['abstract'], 'content1');
        $this->assertEquals($test['keywords'], 'content2');
    }

    public function testGetAll()
    {
        $u = new Content($this->container);
        $this->assertEquals(4, count($u->getList()));
    }
}
