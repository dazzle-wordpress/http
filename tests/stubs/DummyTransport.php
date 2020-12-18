<?php
/**
 * Part of the Dazzle WordPress URI Package.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    Dazzle.WordPress
 * @subpackage Http
 * @author     Dazzle Software <support@dazzlesoftware.org>
 * @copyright  Copyright (C) 2018 - 2020 Dazzle Software, LLC. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.md
 * @link       https://github.com/dazzle-wordpress/http
 * @since      1.0.0
 */

namespace Dazzle\Http\Transport;

/**
 * HTTP transport class for testing purpose only.
 *
 * @since  1.1.4
 */
class DummyTransport
{
	/**
	 * Method to check if HTTP transport DummyTransport is available for use
	 *
	 * @return  boolean  True if available, else false
	 *
	 * @since   1.1.4
	 */
	public static function isSupported()
	{
		return false;
	}
}
