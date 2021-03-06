<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017-2019 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Categories\Traits;

defined('_JEXEC') || die;

use Phproberto\Joomla\Entity\Categories\Column;
use Phproberto\Joomla\Entity\Categories\Category;

/**
 * Trait for entities that have an asset. Based on category_id|catid column.
 *
 * @since  1.0.0
 */
trait HasCategory
{
	/**
	 * Associated category.
	 *
	 * @var  Category
	 */
	protected $category;

	/**
	 * Get the attached database row.
	 *
	 * @return  array
	 */
	abstract public function all();

	/**
	 * Get the associated category.
	 *
	 * @param   boolean  $reload  Force reloading
	 *
	 * @return  Category
	 */
	public function category($reload = false)
	{
		if ($reload || null === $this->category)
		{
			$this->category = $this->loadCategory();
		}

		return $this->category;
	}

	/**
	 * Get associated category identifier.
	 *
	 * @return  integer
	 *
	 * @since   1.7.2
	 */
	public function categoryId()
	{
		$column = $this->getColumnCategory();

		if (!$this->has($column))
		{
			return 0;
		}

		return (int) $this->get($column);
	}

	/**
	 * Get the name of the column that stores category.
	 *
	 * @return  string
	 *
	 * @deprecated  1.7.2  Use column aliases
	 */
	protected function getColumnCategory()
	{
		return $this->columnAlias(Column::CATEGORY);
	}

	/**
	 * Check if this entity has an associated category.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function hasCategory()
	{
		return 0 !== $this->categoryId();
	}

	/**
	 * Load the category from the database.
	 *
	 * @return  Category
	 */
	protected function loadCategory()
	{
		$id = $this->categoryId();

		return $id ? Category::find($id) : new Category;
	}
}
