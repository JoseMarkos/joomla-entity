<?php
/**
 * Virtual storage for objects.
 *
 * @copyright  Copyright (C) 2017-2019 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Tests;

use Phproberto\Joomla\Entity\Tests\Stubs\EntityWithCustomPrimaryKey;

/**
 * Entity test.
 *
 * @since   1.1.0
 */
class EntityWithCustomPrimaryKeyTest extends EntityTest
{
	/**
	 * Name of the primary key
	 *
	 * @const
	 */
	const PRIMARY_KEY = 'entity_id';
}
