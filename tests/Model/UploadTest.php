<?php
require_once __DIR__ . '/../Base.php';

use Model\Upload;

class UploadTest extends Base
{

    public function testGetById()
    {
        $u = new Upload($this->container);
        $this->assertEquals(0, count($u->getAll()));
        $this->assertEmpty($u->getById(0));
        $this->assertEmpty($u->getById(10));
        
        $this->assertNotFalse($u->update(array(
            'title' => 'Test',
            'filename' => 'test.png',
            'thumb' => 'test_thumb.png',
            'type' => Upload::TYPE_IMG
        )));
        $this->assertEquals(1, count($u->getAll()));
        $this->assertEmpty($u->getById(10));
        $test = $u->getById(1);
        $this->assertNotEmpty($test);
        $this->assertEquals(1, $test['id']);
        $this->assertEquals('Test', $test['title']);
        $this->assertEquals('test.png', $test['filename']);
        $this->assertEquals('test_thumb.png', $test['thumb']);
        $this->assertEquals(Upload::TYPE_IMG, $test['type']);
        $this->assertNotFalse($u->update(array(
            'title' => 'Test file',
            'filename' => 'test.pdf',
        )));
        $this->assertEquals(2, count($u->getAll()));
        $this->assertEmpty($u->getById(10));
        $test = $u->getById(2);
        $this->assertNotEmpty($test);
        $this->assertEquals(2, $test['id']);
        $this->assertEquals('Test file', $test['title']);
        $this->assertEquals('test.pdf', $test['filename']);
        $this->assertEquals(Upload::TYPE_FILE, $test['type']);
        
        $this->assertNotFalse($u->update(array(
            'id' => 1,
            'filename' => 'test2.png',
        )));
        $this->assertEquals(2, count($u->getAll()));
        $this->assertEmpty($u->getById(10));
        $test = $u->getById(1);
        $this->assertNotEmpty($test);
        $this->assertEquals(1, $test['id']);
        $this->assertEquals('Test', $test['title']);
        $this->assertEquals('test2.png', $test['filename']);
        $this->assertEquals('test_thumb.png', $test['thumb']);
        $this->assertEquals(Upload::TYPE_IMG, $test['type']);
    }

    public function testGetAll()
    {
        $u = new Upload($this->container);
        $this->assertEquals(0, count($u->getAll()));
        
        $this->assertNotFalse($u->update(array(
            'title' => 'Test',
            'filename' => 'test.png',
            'thumb' => 'test_thumb.png',
            'type' => Upload::TYPE_IMG
        )));
        $this->assertEquals(1, count($u->getAll()));
        $this->assertEquals(1, count($u->getAll(Upload::TYPE_IMG)));
        $this->assertEquals(0, count($u->getAll(Upload::TYPE_FILE)));
        
        $this->assertNotFalse($u->update(array(
            'title' => 'Test file',
            'filename' => 'test.pdf',
        )));
        $this->assertEquals(2, count($u->getAll()));
        $this->assertEquals(1, count($u->getAll(Upload::TYPE_IMG)));
        $this->assertEquals(1, count($u->getAll(Upload::TYPE_FILE)));
        
        $this->assertTrue($u->remove(2));
        $this->assertEquals(1, count($u->getAll()));
        $this->assertEquals(1, count($u->getAll(Upload::TYPE_IMG)));
        $this->assertEquals(0, count($u->getAll(Upload::TYPE_FILE)));
    }
}
