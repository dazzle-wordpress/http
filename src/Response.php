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

namespace Dazzle\Http;

use Laminas\Diactoros\Response as PsrResponse;

/**
 * HTTP response data object class.
 *
 * @property-read  string   $body     The response body as a string
 * @property-read  integer  $code     The status code of the response
 * @property-read  array    $headers  The headers as an array
 *
 * @since  1.0
 */
class Response extends PsrResponse
{
	/**
	 * Magic getter for backward compatibility with the 1.x API
	 *
	 * @param   string  $name  The variable to return
	 *
	 * @return  mixed
	 *
	 * @since   2.0.0-beta
	 * @deprecated  3.0  Access data via the PSR-7 ResponseInterface instead
	 */
	public function __get($name)
	{
		switch (strtolower($name))
		{
			case 'body':
				return (string) $this->getBody();

			case 'code':
				return $this->getStatusCode();

			case 'headers':
				return $this->getHeaders();

			default:
				$trace = debug_backtrace();

				trigger_error(
					sprintf(
						'Undefined property via __get(): %s in %s on line %s',
						$name,
						$trace[0]['file'],
						$trace[0]['line']
					),
					E_USER_NOTICE
				);

				break;
		}
	}
}
