<?php
require './vendor/autoload.php';

$node = new \Circuit\Structure\Element\Node('n1');
$entryPoint = new \Circuit\Structure\Element\EntryPoint('ep1');
$emptyField = new \Circuit\Structure\Element\EmptyField('ef1');

$node->connect($entryPoint);

$emptyField->connect($node);

$map = $emptyField->formStructure();

$structure = new \Circuit\Structure('s1', $map);

var_dump($structure->process()->value());
