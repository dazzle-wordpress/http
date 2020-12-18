<?php
declare(strict_types=1);

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

namespace Dazzle\Http\Exception;

use Psr\Http\Client\ClientExceptionInterface;

/**
 * Exception representing an invalid or undefined HTTP response code
 *
 * @since  1.0.0
 */
class InvalidResponseCodeException extends \UnexpectedValueException implements ClientExceptionInterface
{
}
