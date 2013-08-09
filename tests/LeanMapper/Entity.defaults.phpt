<?php

use LeanMapper\DefaultMapper;
use LeanMapper\Entity;
use LeanMapper\Reflection\EntityReflection;
use LeanMapper\Reflection\PropertyFactory;
use Tester\Assert;
use LeanMapper\Connection;

require_once __DIR__ . '/../bootstrap.php';

////////////////////

class EmptyEntity extends Entity
{
}

$entityReflection = new EntityReflection(new EmptyEntity());

////////////////////

///// booleans

$property = PropertyFactory::createFromAnnotation('property', 'bool $active = true', $entityReflection);

Assert::equal(true, $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'bool $active =    TrUe', $entityReflection);

Assert::equal(true, $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'bool $active =FALSE', $entityReflection);

Assert::equal(false, $property->getDefaultValue());

Assert::exception(function () use ($entityReflection) {
	PropertyFactory::createFromAnnotation('property', 'bool $active = foobar', $entityReflection);
}, 'LeanMapper\Exception\InvalidAnnotationException', "Property of type boolean cannot have default value 'foobar'.");

///// integers

$property = PropertyFactory::createFromAnnotation('property', 'int $count = 10', $entityReflection);

Assert::equal(10, $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'int $count = 10', $entityReflection);

Assert::equal(10, $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'int $count = 0x1F', $entityReflection);

Assert::equal(31, $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'int $count = 0123', $entityReflection);

Assert::equal(83, $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'int $count = "12"', $entityReflection);

Assert::equal(12, $property->getDefaultValue());

Assert::exception(function () use ($entityReflection) {
	PropertyFactory::createFromAnnotation('property', 'int $count = true', $entityReflection);
}, 'LeanMapper\Exception\InvalidAnnotationException', "Property of type integer cannot have default value 'true'.");

$property = PropertyFactory::createFromAnnotation('property', 'int $count =-12', $entityReflection);

Assert::equal(-12, $property->getDefaultValue());

///// floats

$property = PropertyFactory::createFromAnnotation('property', 'float $count = 10.5', $entityReflection);

Assert::equal(10.5, $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'float $count = -2.2e-3', $entityReflection);

Assert::equal(-0.0022, $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'float $count = "-2.4e-3"', $entityReflection);

Assert::equal(-0.0024, $property->getDefaultValue());

///// strings

$property = PropertyFactory::createFromAnnotation('property', 'string $title = test', $entityReflection);

Assert::equal('test', $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'string $title = foo bar', $entityReflection);

Assert::equal('foo', $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'string $title = "foo bar"', $entityReflection);

Assert::equal('foo bar', $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', "string \$title = McDonald's", $entityReflection);

Assert::equal("McDonald's", $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', "string \$title = 'McDonald\'s restaurant'", $entityReflection);

Assert::equal("McDonald's restaurant", $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', "string \$title = 'McDonald\\'s restaurant'", $entityReflection);

Assert::equal("McDonald's restaurant", $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'string $title = "foo\"b\'ar"', $entityReflection);

Assert::equal('foo"b\'ar', $property->getDefaultValue());

///// arrays

$property = PropertyFactory::createFromAnnotation('property', 'array $list = array()', $entityReflection);

Assert::equal(array(), $property->getDefaultValue());

$property = PropertyFactory::createFromAnnotation('property', 'array $list = ARRAY()', $entityReflection);

Assert::equal(array(), $property->getDefaultValue());

Assert::exception(function () use ($entityReflection) {
	PropertyFactory::createFromAnnotation('property', 'array $list = ARRAY', $entityReflection);
}, 'LeanMapper\Exception\InvalidAnnotationException', "Property of type array cannot have default value 'ARRAY'.");