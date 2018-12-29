<?php
require './vendor/autoload.php';
$node = new \Circuit\Simple\Structure\Element\Node('n1');
$entryPoint = new \Circuit\Simple\Structure\Element\EntryPoint('ep1');
$emptyField = new \Circuit\Simple\Structure\Element\EmptyField('ef1');

$node->connect($entryPoint);

$emptyField->connect($node);

$map = $emptyField->formStructure();

var_dump($map['connections']);

$structure = new \Circuit\Simple\Structure('s1', $map);

var_dump($structure);
