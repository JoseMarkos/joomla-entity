<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Tests\Core\Decorator;

use Phproberto\Joomla\Entity\Tests\Stubs\Entity;
use Phproberto\Joomla\Entity\Core\Decorator\TranslatorWithFallback;
use Phproberto\Joomla\Entity\Tests\Core\Decorator\Stubs\TranslatableEntity;

/**
 * TranslatorWithFallback decorator tests.
 *
 * @since   __DEPLOY_VERSION__
 */
class TranslatorWithFallbackTest extends \TestCase
{
	/**
	 * translateIf returns correct value.
	 *
	 * @return  void
	 */
	public function testTranslateReturnsCorrectValue()
	{
		$spanishTranslation = $this->getMockBuilder('MockedTranslation')
			->setMethods(array('get'))
			->getMock();

		$spanishTranslation->method('get')
			->with($this->equalTo('property'))
			->will($this->onConsecutiveCalls('translatedValue', 'validValue', 'invalidValue', 'validValue'));

		$entity = new TranslatableEntity;
		$entity->bind(array('id' => 999, 'language' => 'en-GB', 'property' => 'entityValue'));

		$translator = $this->getMockBuilder(TranslatorWithFallback::class)
			->disableOriginalConstructor()
			->setMethods(array('translation', 'isEntityLanguage'))
			->getMock();

		$translator->method('translation')
			->willReturn($spanishTranslation);

		$translator->method('isEntityLanguage')
			->willReturn(false);

		$reflection = new \ReflectionClass($translator);

		$entityProperty = $reflection->getProperty('entity');
		$entityProperty->setAccessible(true);
		$entityProperty->setValue($translator, $entity);

		$langTagProperty = $reflection->getProperty('langTag');
		$langTagProperty->setAccessible(true);
		$langTagProperty->setValue($translator, 'es-ES');

		$translator->addRule(
			function ($value) {
				return in_array($value, array('validValue'), true);
			},
			'property',
			'testValidValue'
		);

		$this->assertSame('defaultValue', $translator->translate('property', 'defaultValue'));
		$this->assertSame('validValue', $translator->translate('property', 'defaultValue'));

		$rulesProperty = $reflection->getProperty('rules');
		$rulesProperty->setAccessible(true);
		$rulesProperty->setValue($translator, array());

		$translator->addRule(
			function ($value) {
				return in_array($value, array('validValue', 'entityValue'), true);
			},
			'property',
			'testValidValue'
		);

		$this->assertSame('entityValue', $translator->translate('property', 'defaultValue'));
		$this->assertSame('validValue', $translator->translate('property', 'defaultValue'));
	}
}
