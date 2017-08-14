<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Core\Traits;

use Phproberto\Joomla\Entity\Users\User;
use Phproberto\Joomla\Entity\Core\Decorator\Acl;

/**
 * Trait for entities with ACL.
 *
 * @since   __DEPLOY_VERSION__
 */
trait HasAcl
{
	/**
	 * Retrieve the associated component.
	 *
	 * @return  Component
	 */
	abstract public function component();

	/**
	 * Check if this entity has an id.
	 *
	 * @return  boolean
	 */
	abstract public function hasId();

	/**
	 * Get the entity identifier.
	 *
	 * @return  integer
	 */
	abstract public function id();

	/**
	 * Get this entity name.
	 *
	 * @return  string
	 */
	abstract public function name();

	/**
	 * Acl instance.
	 *
	 * @param   User|null  $user  User to check ACL against.
	 *
	 * @return  Acl
	 */
	public function acl(User $user = null)
	{
		return new Acl($this, $user);
	}

	/**
	 * Get the ACL prefix applied to this entity
	 *
	 * @return  string
	 */
	public function aclPrefix()
	{
		return 'core';
	}

	/**
	 * Get the identifier of the project asset
	 *
	 * @return  string
	 */
	public function aclAssetName()
	{
		if ($this->hasId())
		{
			return $this->component()->option() . '.' . $this->name() . '.' . $this->id();
		}

		return $this->component()->option();
	}
}
