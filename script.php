<?php
require './vendor/autoload.php';
$structure = new \Circuit\Simple\Structure();
$node = new \Circuit\Simple\Structure\Element\Node();
$entryPoint = new \Circuit\Simple\Structure\Element\EntryPoint();
$emptyField = new \Circuit\Simple\Structure\Element\EmptyField();

$node->connect($entryPoint);

$structure->append($node);