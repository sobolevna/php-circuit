<?php
require './vendor/autoload.php';

$node1 = new \Circuit\Structure\Element\Node('n1');
$node2 = new \Circuit\Structure\Element\Node('n2');
$entryPoint1 = new \Circuit\Structure\Element\EntryPoint('ep1');
$entryPoint2 = new \Circuit\Structure\Element\EntryPoint('ep2');
$emptyField1 = new \Circuit\Structure\Element\EmptyField('ef1');

$node1->connect($entryPoint1);
//$node2->connect($entryPoint2);
//$node1->connect($node2);

$emptyField1->connect($node1);

$map = $emptyField1->formStructure();

$structure = new \Circuit\Structure('s1', $map);

print_r($structure->process());
