<?php
namespace Model;

interface ActualiteDAO
{

    public function findActualiteById($id);

    public function findActualites($page = 1, $memeLesDesactives = false, $nbParPage = NbParPage);

    public function calculNbActualites($memeLesDesactives = false);
}
