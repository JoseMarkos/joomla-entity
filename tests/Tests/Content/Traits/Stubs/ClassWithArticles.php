<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Tests\Content\Traits\Stubs;

use Phproberto\Joomla\Entity\Entity;
use Phproberto\Joomla\Entity\EntityCollection;
use Phproberto\Joomla\Entity\Content\Article;
use Phproberto\Joomla\Entity\Content\Traits\HasArticles;

/**
 * Sample class to test HasArticles trait.
 *
 * @since  __DEPLOY_VERSION__
 */
class ClassWithArticles extends Entity
{
	use HasArticles;

	/**
	 * Expected articles ids for testing.
	 *
	 * @var  array
	 */
	public $articlesIds = array();

	/**
	 * Load associated articles from DB.
	 *
	 * @return  EntityCollection
	 */
	protected function loadArticles()
	{
		$collection = new EntityCollection;

		foreach ($this->articlesIds as $id)
		{
			$collection->add(new Article($id));
		}

		return $collection;
	}
}
