<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Tests\Core\Traits;

use Phproberto\Joomla\Entity\Collection;
use Phproberto\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithTranslations;

/**
 * HasTranslations trait tests.
 *
 * @since   __DEPLOY_VERSION__
 */
class HasTranslationsTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		EntityWithTranslations::clearAllInstances();

		parent::tearDown();
	}

	/**
	 * hasTranslation returns correct value.
	 *
	 * @return  void
	 */
	public function testHastranslationReturnsCorrectValue()
	{
		$entity = new EntityWithTranslations;

		$this->assertSame(false, $entity->hasTranslation('es-ES'));
		$this->assertSame(false, $entity->hasTranslation('es-AR'));
		$this->assertSame(false, $entity->hasTranslation('pt-BR'));

		$translations = array(
			'es-ES' => EntityWithTranslations::instance(666),
			'pt-BR' => EntityWithTranslations::instance(999)
		);

		$entity = $this->getMockBuilder(EntityWithTranslations::class)
			->setMethods(array('translationsByTag'))
			->getMock();

		$entity->expects($this->exactly(3))
			->method('translationsByTag')
			->willReturn($translations);

		$this->assertSame(true, $entity->hasTranslation('es-ES'));
		$this->assertSame(false, $entity->hasTranslation('es-AR'));
		$this->assertSame(true, $entity->hasTranslation('pt-BR'));
	}

	/**
	 * hasTranslations returns correct value.
	 *
	 * @return  void
	 */
	public function testHasTranslationsReturnsCorrectValue()
	{
		$entity = new EntityWithTranslations;

		$this->assertSame(false, $entity->hasTranslations());

		$translations = new Collection(
			array(
				EntityWithTranslations::instance(666),
				EntityWithTranslations::instance(999)
			)
		);

		$entity = $this->getMockBuilder(EntityWithTranslations::class)
			->setMethods(array('translations'))
			->getMock();

		$entity->expects($this->once())
			->method('translations')
			->willReturn($translations);

		$this->assertSame(true, $entity->hasTranslations());
	}

	/**
	 * translate returns translation property.
	 *
	 * @return  void
	 */
	private function TranslateReturnsTranslationProperty()
	{
		$activeLanguage = $this->getMockBuilder('MockedLanguage')
			->setMethods(array('getTag'))
			->getMock();

		$activeLanguage->method('getTag')
			->willReturn('es-ES');

		$spanishTranslation = $this->getMockBuilder('MockedTranslation')
			->setMethods(array('get'))
			->getMock();

		$spanishTranslation->method('get')
			->with($this->equalTo('property'))
			->will($this->onConsecutiveCalls('translatedValue', $this->returnArgument(1)));

		$entity = $this->getMockBuilder(EntityWithTranslations::class)
			->setMethods(array('activeLanguage', 'columnAlias', 'translation'))
			->getMock();

		$entity->method('activeLanguage')
			->willReturn($activeLanguage);

		$entity->method('columnAlias')
			->willReturn('language');

		$entity->method('translation')
			->with($this->equalTo('es-ES'))
			->willReturn($spanishTranslation);

		$entity->bind(array('id' => 999, 'language' => 'en-GB', 'property' => 'value'));

		$this->assertSame('translatedValue', $entity->translate('property'));
		$this->assertSame('defaultValue', $entity->translate('property', 'defaultValue'));
	}

	/**
	 * translation returns correct value.
	 *
	 * @return  void
	 */
	public function testTranslationRetursnCorrectValue()
	{
		$translations = array(
			'es-ES' => EntityWithTranslations::instance(666),
			'pt-BR' => EntityWithTranslations::instance(999)
		);

		$entity = new EntityWithTranslations;
		$reflection = new \ReflectionClass($entity);

		$translationsProperty = $reflection->getProperty('translationsByTag');
		$translationsProperty->setAccessible(true);
		$translationsProperty->setValue($entity, $translations);

		$this->assertSame(EntityWithTranslations::instance(666), $entity->translation('es-ES'));
	}

	/**
	 * translation throws an exception trying to retrieve a missing translation.
	 *
	 * @return  void
	 *
	 * @expectedException  \InvalidArgumentException
	 */
	public function testTranslationThrowsExceptionForMissingTranslation()
	{
		$entity = new EntityWithTranslations;

		$entity->translation('es-ES');
	}

	/**
	 * translationsByTag returns correct data.
	 *
	 * @return  void
	 */
	public function testTranslationsByTagReturnsCorrectData()
	{
		$entity = new EntityWithTranslations;

		$reflection = new \ReflectionClass($entity);

		$idProperty = $reflection->getProperty('id');
		$idProperty->setAccessible(true);
		$rowProperty = $reflection->getProperty('row');
		$rowProperty->setAccessible(true);

		$this->assertEquals(array(), $entity->translationsByTag());

		$entities = array(
			666 => array('id' => 666, 'title' => 'Spanish translation', 'lang' => 'es-ES'),
			999 => array('id' => 999, 'title' => 'Brasialian translation', 'lang' => 'pt-BR')
		);

		$spanish = new EntityWithTranslations(666);
		$rowProperty->setValue($spanish, $entities[666]);

		$brasilian = new EntityWithTranslations(999);
		$rowProperty->setValue($brasilian, $entities[999]);

		$tableMock = $this->getMockBuilder('TableMock')
			->disableOriginalConstructor()
			->setMethods(array('getColumnAlias'))
			->getMock();

		$tableMock->expects($this->exactly(2))
			->method('getColumnAlias')
			->willReturn('lang');

		$entity = $this->getMockBuilder(EntityWithTranslations::class)
			->setMethods(array('table', 'translations'))
			->getMock();

		$entity->expects($this->exactly(2))
			->method('table')
			->willReturn($tableMock);

		$entity->expects($this->once())
			->method('translations')
			->willReturn(new Collection(array($spanish, $brasilian)));

		$expected = array(
			'es-ES' => $spanish,
			'pt-BR' => $brasilian
		);

		$this->assertSame($expected, $entity->translationsByTag());
	}

	/**
	 * translations returns expected translations.
	 *
	 * @return  void
	 */
	public function testTranslationsReturnsExpectedTranslatons()
	{
		$entity = new EntityWithTranslations;

		$this->assertEquals(new Collection, $entity->translations());

		$entity->translationsIds = array(666, 999);

		$expected = new Collection(
			array(
				EntityWithTranslations::instance(666),
				EntityWithTranslations::instance(999)
			)
		);

		// No reload = same data
		$this->assertEquals(new Collection, $entity->translations());
		$this->assertEquals($expected, $entity->translations(true));
	}
}
