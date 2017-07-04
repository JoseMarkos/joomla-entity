<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Tests\Traits\Stubs;

use Phproberto\Joomla\Entity\Entity;
use Phproberto\Joomla\Entity\Traits\HasUrls;

/**
 * Sample entity to test HasUrls trait.
 *
 * @since  __DEPLOY_VERSION__
 */
class EntityWithUrls extends Entity
{
	use HasUrls;
}
