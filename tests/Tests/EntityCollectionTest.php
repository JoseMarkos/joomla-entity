<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Tests;

use Phproberto\Joomla\Entity\Tests\Stubs\Entity;
use Phproberto\Joomla\Entity\EntityCollection;

/**
 * Entity collection tests.
 *
 * @since   __DEPLOY_VERSION__
 */
class EntityCollectionTest extends \TestCase
{
	/**
	 * Constructor sets entities.
	 *
	 * @return  void
	 */
	public function testConstructorSetsEntities()
	{
		$collection = new EntityCollection;

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertSame(array(), $entitiesProperty->getValue($collection));

		$entities = array(
			new Entity(1000),
			new Entity(1001)
		);

		$collection = new EntityCollection($entities);

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertEquals(
			array(
				1000 => new Entity(1000),
				1001 => new Entity(1001)
			),
			$entitiesProperty->getValue($collection)
		);
	}

	/**
	 * add adds a new entity.
	 *
	 * @return  void
	 */
	public function testAddAddsNewEntity()
	{
		$collection = new EntityCollection;

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertSame(array(), $entitiesProperty->getValue($collection));

		$entity = new Entity(1000);

		$this->assertTrue($collection->add($entity));

		$this->assertSame(array(1000 => $entity), $entitiesProperty->getValue($collection));

		$entity1 = new Entity(1001);

		$this->assertTrue($collection->add($entity1));

		$this->assertSame(array(1000 => $entity, 1001 => $entity1), $entitiesProperty->getValue($collection));
	}

	/**
	 * add throws exception when entity has no id.
	 *
	 * @return  void
	 *
	 * @expectedException  \InvalidArgumentException
	 *
	 */
	public function testAddThrowsExceptionWhenEntityHasNoId()
	{
		$collection = new EntityCollection;

		$collection->add(new Entity);
	}

	/**
	 * add does not overwrite entity.
	 *
	 * @return  void
	 */
	public function testAddDoesNotOverwriteEntity()
	{
		$collection = new EntityCollection;

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertSame(array(), $entitiesProperty->getValue($collection));

		$entity = new Entity(1000);
		$entity2 = new Entity(1000);
		$entity3 = new Entity(1001);

		$reflection = new \ReflectionClass($entity);
		$rowProperty = $reflection->getProperty('row');
		$rowProperty->setAccessible(true);

		$expectedRow = array('id' => 1000, 'name' => 'Roberto Segura');

		$rowProperty->setValue($entity, $expectedRow);

		$this->assertTrue($collection->add($entity));

		$this->assertSame(array(1000 => $entity), $entitiesProperty->getValue($collection));

		$this->assertFalse($collection->add($entity2));

		$this->assertNotSame(array(1000 => $entity2), $entitiesProperty->getValue($collection));
	}

	/**
	 * clear empties entities array.
	 *
	 * @return  void
	 */
	public function testClearEmptiesEntitiesArray()
	{
		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001), new Entity(1002)));

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertSame(3, count($entitiesProperty->getValue($collection)));

		$collection->clear();

		$this->assertSame(array(), $entitiesProperty->getValue($collection));
	}

	/**
	 * count returns correct value.
	 *
	 * @return  void
	 */
	public function testCountReturnsCorrectValue()
	{
		$collection = new EntityCollection;

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertSame(0, $collection->count());

		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001)));

		$this->assertSame(2, $collection->count());

		$entitiesProperty->setValue(
			$collection,
			array(
				1000 => new Entity(1000),
				1001 => new Entity(1001),
				1002 => new Entity(1002)
			)
		);

		$this->assertSame(3, $collection->count());
	}

	/**
	 * current returns correct value.
	 *
	 * @return  void
	 */
	public function testCurrentReturnsCorrectValue()
	{
		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001), new Entity(1002)));

		foreach ($collection as $entity)
		{
			$this->assertSame($collection->current(), $entity);
		}

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001), new Entity(1002)));

		$entities = array(
			1000 => new Entity(1000),
			1001 => new Entity(1001),
			1002 => new Entity(1002)
		);

		$entitiesProperty->setValue($collection, $entities);

		$this->assertEquals(new Entity(1000), $collection->current());

		while (key($entities) !== 1001)
		{
		    next($entities);
		}

		$entitiesProperty->setValue($collection, $entities);

		$this->assertEquals(new Entity(1001), $collection->current());
	}

	/**
	 * has returns correct vlaue.
	 *
	 * @return  void
	 */
	public function testHasReturnsCorrectValue()
	{
		$collection = new EntityCollection;

		$this->assertFalse($collection->has(1000));
		$this->assertFalse($collection->has(1001));
		$this->assertFalse($collection->has(1002));

		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001)));

		$this->assertTrue($collection->has(1000));
		$this->assertFalse($collection->has(1002));
		$this->assertTrue($collection->has(1001));
	}

	/**
	 * ids returns correct identifiers.
	 *
	 * @return  void
	 */
	public function testIdsReturnsCorrectIdentifiers()
	{
		$collection = new EntityCollection;

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertSame(array(), $collection->ids());

		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001)));

		$this->assertSame(array(1000, 1001), $collection->ids());

		$entitiesProperty->setValue(
			$collection,
			array(
				1000 => new Entity(1000),
				1002 => new Entity(1002),
				1001 => new Entity(1001)
			)
		);

		$this->assertSame(array(1000, 1002, 1001), $collection->ids());
	}

	/**
	 * isEmpty returns correct value.
	 *
	 * @return  void
	 */
	public function testIsEmptyReturnsCorrectValue()
	{
		$collection = new EntityCollection;

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertTrue($collection->isEmpty());

		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001)));

		$this->assertFalse($collection->isEmpty());

		$entitiesProperty->setValue($collection, array());

		$this->assertTrue($collection->isEmpty());

		$entitiesProperty->setValue(
			$collection,
			array(
				1000 => new Entity(1000),
				1002 => new Entity(1002),
				1001 => new Entity(1001)
			)
		);

		$this->assertFalse($collection->isEmpty());
	}

	/**
	 * key returns correct value.
	 *
	 * @return  void
	 */
	public function testKeyReturnsCorrectValue()
	{
		$collection = new EntityCollection;

		$this->assertSame(null, $collection->key());

		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001), new Entity(1002)));

		foreach ($collection as $entity)
		{
			$this->assertSame($collection->key(), $entity->getId());
		}

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001), new Entity(1002)));

		$entities = array(
			1000 => new Entity(1000),
			1001 => new Entity(1001),
			1002 => new Entity(1002)
		);

		$entitiesProperty->setValue($collection, $entities);

		$this->assertSame(1000, $collection->key());

		while (key($entities) !== 1001)
		{
			next($entities);
		}

		$entitiesProperty->setValue($collection, $entities);

		$this->assertSame(1001, $collection->key());
	}

	/**
	 * next returns correct value.
	 *
	 * @return  void
	 */
	public function testNextReturnsCorrectValue()
	{
		$collection = new EntityCollection;

		$this->assertSame(false, $collection->next());

		$entities = array(1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002));

		$collection = new EntityCollection($entities);

		foreach ($collection as $entity)
		{
			if ($entity->getId() !== 1002)
			{
				$this->assertSame($collection->next(), $entities[$entity->getId() + 1]);
			}
		}

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$collection = new EntityCollection(array(new Entity(1000), new Entity(1001), new Entity(1002)));

		$entitiesProperty->setValue($collection, $entities);

		$this->assertSame($entities[1001], $collection->next());

		while (key($entities) !== 1001)
		{
			next($entities);
		}

		$entitiesProperty->setValue($collection, $entities);

		$this->assertSame($entities[1002], $collection->next());
	}

	/**
	 * remove removes entity.
	 *
	 * @return  void
	 */
	public function testRemoveRemovesEntity()
	{
		$collection = new EntityCollection;

		$this->assertSame(false, $collection->remove(1000));

		$entities = array(1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002));

		$collection = new EntityCollection($entities);

		$this->assertSame(false, $collection->remove(1005));

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertSame(true, $collection->remove(1001));
		$this->assertEquals(array(1000 => new Entity(1000), 1002 => new Entity(1002)), $entitiesProperty->getValue($collection));

		$this->assertSame(true, $collection->remove(1000));
		$this->assertEquals(array(1002 => new Entity(1002)), $entitiesProperty->getValue($collection));

		$this->assertSame(true, $collection->remove(1002));
		$this->assertEquals(array(), $entitiesProperty->getValue($collection));
	}

	/**
	 * rewind returns correct value.
	 *
	 * @return  void
	 */
	public function testRewindReturnsCorrectValue()
	{
		$collection = new EntityCollection;

		$this->assertSame(false, $collection->rewind());

		$entities = array(1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002));

		$collection = new EntityCollection($entities);

		$this->assertSame($entities[1000], $collection->rewind());

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		while (key($entities) !== 1001)
		{
			next($entities);
		}

		$entitiesProperty->setValue($collection, $entities);

		$this->assertSame(1001, key($entitiesProperty->getValue($collection)));
		$this->assertEquals(new Entity(1000), $collection->rewind());
	}

	/**
	 * valid returns correct value.
	 *
	 * @return  void
	 */
	public function testValidReturnsCorrectValue()
	{
		$collection = new EntityCollection;

		$this->assertFalse($collection->valid());

		$entities = array(1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002));

		$collection = new EntityCollection($entities);

		$this->assertTrue($collection->valid());
	}

	/**
	 * set sets correct value.
	 *
	 * @return  void
	 */
	public function testWriteOverwritesValue()
	{
		$collection = new EntityCollection;

		$this->assertTrue($collection->write(new Entity(1000)));

		$reflection = new \ReflectionClass($collection);
		$entitiesProperty = $reflection->getProperty('entities');
		$entitiesProperty->setAccessible(true);

		$this->assertEquals(array(1000 => new Entity(1000)), $entitiesProperty->getValue($collection));

		$entities = array(1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002));

		$entity = new Entity(1000);
		$entity2 = new Entity(1000);
		$entity3 = new Entity(1001);

		$reflection = new \ReflectionClass($entity);
		$rowProperty = $reflection->getProperty('row');
		$rowProperty->setAccessible(true);

		$expectedRow = array('id' => 1000, 'name' => 'Roberto Segura');

		$rowProperty->setValue($entity, $expectedRow);

		$this->assertTrue($collection->write($entity));

		$this->assertSame(array(1000 => $entity), $entitiesProperty->getValue($collection));

		$this->assertTrue($collection->write($entity2));

		$this->assertSame(array(1000 => $entity2), $entitiesProperty->getValue($collection));
	}
}