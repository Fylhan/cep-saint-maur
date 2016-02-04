<?php
namespace Core;

class MVCException extends \Exception
{

    private $page;

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }
}
?>