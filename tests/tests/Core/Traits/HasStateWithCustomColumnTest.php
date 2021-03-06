<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017-2019 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Tests\Core\Traits;

use Phproberto\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithState;

/**
 * HasState trait tests.
 *
 * @since   1.1.0
 */
class HasStateWithCustomColumnTest extends HasStateTest
{
	/**
	 * Column to use to load/store state.
	 *
	 * @const
	 */
	const COLUMN_STATE = 'state';
}
