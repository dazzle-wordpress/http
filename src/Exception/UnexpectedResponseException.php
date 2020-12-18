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

use Dazzle\Http\Response;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Exception representing an unexpected response
 *
 * @since  1.0.0
 */
class UnexpectedResponseException extends \DomainException implements ClientExceptionInterface
{
    /**
     * The Response object.
     *
     * @var    Response
     * @since  1.0.0
     */
    private $response;

    /**
     * Constructor
     *
     * @param   Response    $response  The Response object.
     * @param   string      $message   The Exception message to throw.
     * @param   integer     $code      The Exception code.
     * @param   \Exception  $previous  The previous exception used for the exception chaining.
     *
     * @since   1.0.0
     */
    public function __construct(Response $response, $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->response = $response;
    }

    /**
     * Get the Response object.
     *
     * @return  Response
     *
     * @since   1.0.0
     */
    public function getResponse()
    {
        return $this->response;
    }
}