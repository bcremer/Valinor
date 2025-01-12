<?php

declare(strict_types=1);

namespace CuyZ\Valinor\Tests\Unit\Definition;

use CuyZ\Valinor\Definition\AttributesContainer;
use CuyZ\Valinor\Tests\Fixture\Attribute\BasicAttribute;
use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

final class AttributesContainerTest extends TestCase
{
    public function test_empty_attributes_is_empty_and_remains_the_same_instance(): void
    {
        $attributes = AttributesContainer::empty();

        self::assertSame($attributes, AttributesContainer::empty());
        self::assertCount(0, $attributes);
        self::assertFalse($attributes->has(BasicAttribute::class));
        self::assertEmpty($attributes->ofType(BasicAttribute::class));
    }

    public function test_attributes_are_countable(): void
    {
        $attributes = new AttributesContainer(new stdClass(), new stdClass(), new stdClass());

        self::assertCount(3, $attributes);
    }

    public function test_attributes_are_traversable(): void
    {
        $attributes = [new stdClass(), new stdClass(), new stdClass()];
        $container = new AttributesContainer(...$attributes);

        self::assertSame($attributes, iterator_to_array($container));
    }

    public function test_attributes_has_type_checks_all_attributes(): void
    {
        $attributes = new AttributesContainer(new stdClass());

        self::assertTrue($attributes->has(stdClass::class));
        self::assertFalse($attributes->has(DateTimeInterface::class));
    }

    public function test_attributes_of_type_filters_on_given_class_name(): void
    {
        $object = new stdClass();
        $date = new DateTime();

        $attributes = new AttributesContainer($object, $date);
        $filteredAttributes = $attributes->ofType(DateTimeInterface::class);

        self::assertContainsEquals($date, $filteredAttributes);
        self::assertNotContains($object, $filteredAttributes);
        self::assertSame($date, $filteredAttributes[0]);
    }
}
