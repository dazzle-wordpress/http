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

/**
 * Abstract transport class.
 *
 * @since  2.0.0-beta
 */
abstract class AbstractTransport implements TransportInterface
{
	/**
	 * The client options.
	 *
	 * @var    array|\ArrayAccess
	 * @since  2.0.0-beta
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * @param   array|\ArrayAccess  $options  Client options array.
	 *
	 * @since   2.0.0-beta
	 * @throws  \RuntimeException
	 */
	public function __construct($options = [])
	{
		if (!static::isSupported())
		{
			throw new \RuntimeException(sprintf('The %s transport is not supported in this environment.', \get_class($this)));
		}

		if (!\is_array($options) && !($options instanceof \ArrayAccess))
		{
			throw new \InvalidArgumentException(
				'The options param must be an array or implement the ArrayAccess interface.'
			);
		}

		$this->options = $options;
	}

	/**
	 * Get an option from the HTTP transport.
	 *
	 * @param   string  $key      The name of the option to get.
	 * @param   mixed   $default  The default value if the option is not set.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   2.0.0-beta
	 */
	protected function getOption(string $key, $default = null)
	{
		return $this->options[$key] ?? $default;
	}

	/**
	 * Processes the headers from a transport's response data.
	 *
	 * @param   array  $headers  The headers to process.
	 *
	 * @return  array
	 *
	 * @since   2.0.0-beta
	 */
	protected function processHeaders(array $headers): array
	{
		$verifiedHeaders = [];

		// Add the response headers to the response object.
		foreach ($headers as $header)
		{
			$pos     = strpos($header, ':');
			$keyName = trim(substr($header, 0, $pos));

			if (!isset($verifiedHeaders[$keyName]))
			{
				$verifiedHeaders[$keyName] = [];
			}

			$verifiedHeaders[$keyName][] = trim(substr($header, ($pos + 1)));
		}

		return $verifiedHeaders;
	}
}