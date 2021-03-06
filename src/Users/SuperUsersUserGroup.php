<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017-2019 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Users;

defined('_JEXEC') || die;

use Phproberto\Joomla\Entity\Users\PublicUserGroup;
use Phproberto\Joomla\Entity\Users\PredefinedUserGroup;

/**
 * SuperUsersUserGroup entity.
 *
 * @since   __DEPLOY_VERSION__
 */
class SuperUsersUserGroup extends PredefinedUserGroup
{
	/**
	 * Group title.
	 *
	 * @const
	 */
	const TITLE = 'Super Users';

	/**
	 * Predefined data to load the group.
	 *
	 * @return  array
	 */
	public static function predefinedData()
	{
		return [
			'parent_id' => PublicUserGroup::instanceOrCreate()->id(),
			'title'     => self::TITLE
		];
	}
}
