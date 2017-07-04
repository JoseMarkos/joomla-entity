<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Traits;

/**
 * Trait for entities with urls column.
 *
 * @since   __DEPLOY_VERSION__
 */
trait HasUrls
{
	/**
	 * URLs
	 *
	 * @var  array
	 */
	protected $urls;

	/**
	 * Get the name of the column that stores urls.
	 *
	 * @return  string
	 */
	protected function getColumnUrls()
	{
		return 'urls';
	}

	/**
	 * Get this article URLs.
	 *
	 * @param   boolean  $reload  Force reloading
	 *
	 * @return  array
	 */
	public function getUrls($reload = false)
	{
		if ($reload || null === $this->urls)
		{
			$this->urls = $this->loadUrls();
		}

		return $this->urls;
	}

	/**
	 * Load urls from database.
	 *
	 * @return  array
	 */
	protected function loadUrls()
	{
		$urls = [];
		$data = $this->json($this->getColumnUrls());

		if (empty($data))
		{
			return $urls;
		}

		for ($i = 'a'; $i < 'd'; $i++)
		{
			if ($url = $this->parseUrl($i, $data))
			{
				$urls[$i] = $url;
			}
		}

		return $urls;
	}

	/**
	 * Parse URL.
	 *
	 * @param   string  $position  URL position
	 * @param   array   $data      URLs data source from db
	 *
	 * @return  array
	 */
	private function parseUrl($position, array $data)
	{
		$url = [];

		if (empty($data['url' . $position]))
		{
			return $url;
		}

		$properties = [
			'url'    => 'url' . $position,
			'text'   => 'url' . $position . 'text',
			'target' => 'target' . $position
		];

		foreach ($properties as $key => $property)
		{
			if (isset($data[$property]) && $data[$property] != '')
			{
				$url[$key] = $data[$property];
			}
		}

		return $url;
	}
}
