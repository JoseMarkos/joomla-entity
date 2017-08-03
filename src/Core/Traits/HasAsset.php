<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Core\Traits;

use Phproberto\Joomla\Entity\Core\Asset;
use Phproberto\Joomla\Entity\Core\Column;

defined('JPATH_PLATFORM') || die;

/**
 * Trait for entities that have an asset. Based on asset_id column.
 *
 * @since  __DEPLOY_VERSION__
 */
trait HasAsset
{
	/**
	 * Associated asset.
	 *
	 * @var  Asset
	 */
	protected $asset;

	/**
	 * Get the attached database row.
	 *
	 * @return  array
	 */
	abstract public function all();

	/**
	 * Get the associated asset.
	 *
	 * @param   boolean  $reload  Force asset reloading
	 *
	 * @return  Asset
	 */
	public function getAsset($reload = false)
	{
		if ($reload || null === $this->asset)
		{
			$this->asset = $this->loadAsset();
		}

		return $this->asset;
	}

	/**
	 * Load the asset from the database.
	 *
	 * @return  Asset
	 */
	protected function loadAsset()
	{
		$column = $this->columnAlias(Column::ASSET);
		$data = $this->all();

		if (empty($data[$column]))
		{
			return new Asset;
		}

		return Asset::instance($data[$column]);
	}
}
