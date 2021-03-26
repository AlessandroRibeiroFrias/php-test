<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class FileCollectionTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $collection = new FileCollection();
        return $collection;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded()
    {
        $collection = new FileCollection();
        $collection->set("0", 'String');
        $collection->set("1", 60, 3);
        $collection->set("2", 'Texto 2');
        $collection->set("3", 8, 10);
        $collection->set("position4", [0, 'value2', 3], 10);
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataCanBeRetrieved()
    {
        $collection = new FileCollection();
        $collection->set('1', 'Valor 10', 10);

        $this->assertEquals('Valor 10', $collection->get('1'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function arrayValueMustBeFormated()
    {
        $collection = new FileCollection();
        $collection->set(1, ['value1', 'value2']);

        $this->assertEquals('value1;value2', $collection->get(1));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function inexistentIndexShouldReturnDefaultValue()
    {
        $collection = new FileCollection();
        $this->assertNull($collection->get('index1'));
        $this->assertEquals('defaultValue', $collection->get('index1', 'defaultValue'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function newCollectionShouldNotContainItems()
    {
        $collection = new FileCollection();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function collectionWithItemsShouldReturnValidCount()
    {
        $collection = new FileCollection();
        $collection->set(1, 'value');
        $collection->set(2, 5);
        $collection->set(3, 33);
        $collection->set(4, 'String 4');

        $this->assertEquals(4, $collection->count());
    }

    /**
     * @test
     * @depends collectionWithItemsShouldReturnValidCount
     */
    public function collectionCanBeCleaned()
    {
        $collection = new FileCollection();
        $collection->set(1, 'value');
        $this->assertEquals(1, $collection->count());

        $collection->clean();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function addedItemShouldExistInCollection()
    {
        $collection = new FileCollection();
        $collection->set('1', 'value');

        $this->assertTrue($collection->has('1'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function expiredItemShouldNotReturn()
    {
        $collection = new FileCollection();
        $collection->set('1', 'value', -10);
        $this->assertNull($collection->get('1', 'defaultValue'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function shouldUpdateFile()
    {
        $collection = new FileCollection();
        $collection->set(1, 'value', 60);
        $collection->set(2, 5, 60);
        $collection->set(3, true, 60);

        $this->assertEquals(5, $collection->get(2));

        $collection->set(2, "string", 60);

        $this->assertEquals('string', $collection->get(2, 'defaultValue'));
        $this->assertEquals(3, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function shouldReturnAnExistingIndex()
    {
        $collection = new FileCollection();
        $collection->set(1, 'value', 60);
        $collection->set(2, 5, 60);

        $this->assertTrue($collection->has(1));
        $this->assertFalse($collection->has(3));

        $collection->clean();
    }
}
