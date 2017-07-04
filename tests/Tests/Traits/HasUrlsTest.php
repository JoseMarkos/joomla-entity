<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Tests\Traits;

use Phproberto\Joomla\Entity\Tests\Traits\Stubs\EntityWithUrls;

/**
 * HasUrls trait tests.
 *
 * @since   __DEPLOY_VERSION__
 */
class HasUrlsTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		EntityWithUrls::clearAllInstances();

		parent::tearDown();
	}

	/**
	 * getUrls gets correct data.
	 *
	 * @return  void
	 */
	public function testGetUrlsGetsCorrectData()
	{
		$entity = new EntityWithUrls(999);

		$reflection = new \ReflectionClass($entity);
		$rowProperty = $reflection->getProperty('row');
		$rowProperty->setAccessible(true);

		$rowProperty->setValue($entity, ['id' => 999]);

		$this->assertEquals([], $entity->getUrls());

		$rowProperty->setValue($entity, ['id' => 999, 'urls' => '']);

		$this->assertEquals([], $entity->getUrls(true));

		$rowProperty->setValue($entity, ['id' => 999, 'urls' => '{}']);

		$this->assertEquals([], $entity->getUrls(true));

		$rowProperty->setValue($entity, ['id' => 999, 'urls' => '{"urla":"","urlatext":"","targeta":"","urlb":"","urlbtext":"","targetb":"","urlc":"","urlctext":"","targetc":""}']);

		$this->assertEquals([], $entity->getUrls(true));

		$rowProperty->setValue($entity, ['id' => 999, 'urls' => '{"urla":"http://google.com","urlatext":"Google","targeta":"0"}']);

		// With no reload returns old data
		$this->assertEquals([], $entity->getUrls());

		$expected = [
			'a' => [
				'url'    => 'http://google.com',
				'text'   => 'Google',
				'target' => '0'
			]
		];

		$this->assertEquals($expected, $entity->getUrls(true));

		$rowProperty->setValue($entity, ['id' => 999, 'urls' => '{"urla":"http:\/\/google.es","urlatext":"Google","targeta":"1","urlb":"http:\/\/yahoo.com","urlbtext":"Yahoo","targetb":"0","urlc":"http://www.phproberto.com","urlctext":"Phproberto","targetc":""}']);

		$expected = [
			'a' => [
				'url'    => 'http://google.es',
				'text'   => 'Google',
				'target' => '1'
			],
			'b' => [
				'url'    => 'http://yahoo.com',
				'text'   => 'Yahoo',
				'target' => '0'
			],
			'c' => [
				'url'    => 'http://www.phproberto.com',
				'text'   => 'Phproberto'
			]
		];

		$this->assertEquals($expected, $entity->getUrls(true));
	}

	/**
	 * getUrls works with custom column.
	 *
	 * @return  void
	 */
	public function testGetUrlsWorksWithCustomColumn()
	{
		$entity = $this->getMockBuilder(EntityWithUrls::class)
			->setMethods(array('getColumnUrls'))
			->getMock();

		$entity->method('getColumnUrls')
			->willReturn('links');

		$reflection = new \ReflectionClass($entity);
		$rowProperty = $reflection->getProperty('row');
		$rowProperty->setAccessible(true);

		$rowProperty->setValue($entity, ['id' => 999]);

		$this->assertEquals([], $entity->getUrls());

		$rowProperty->setValue($entity, ['id' => 999, 'links' => '{"urla":"http:\/\/google.es","urlatext":"Google","targeta":"1","urlb":"http:\/\/yahoo.com","urlbtext":"Yahoo","targetb":"0","urlc":"http://www.phproberto.com","urlctext":"Phproberto","targetc":""}']);

		$this->assertEquals([], $entity->getUrls());

		$expected = [
			'a' => [
				'url'    => 'http://google.es',
				'text'   => 'Google',
				'target' => '1'
			],
			'b' => [
				'url'    => 'http://yahoo.com',
				'text'   => 'Yahoo',
				'target' => '0'
			],
			'c' => [
				'url'    => 'http://www.phproberto.com',
				'text'   => 'Phproberto'
			]
		];

		$this->assertEquals($expected, $entity->getUrls(true));
	}
}
