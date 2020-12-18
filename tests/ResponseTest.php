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

namespace Dazzle\Http\Tests;

use Dazzle\Http\Response;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Dazzle\Http\Response.
 */
class ResponseTest extends TestCase
{
	/**
	 * @testdox  The status code can be accessed through the deprecated property access
	 *
	 * @covers   Dazzle\Http\Response
	 */
	public function testReadResponseCode()
	{
		$this->assertSame(
			200,
			(new Response('php://memory', 200, []))->code
		);
	}

	/**
	 * @testdox  The response body can be accessed through the deprecated property access
	 *
	 * @covers   Dazzle\Http\Response
	 */
	public function testReadResponseBody()
	{
		$this->assertSame(
			'',
			(new Response('php://memory', 200, []))->body
		);
	}

	/**
	 * @testdox  The response headers can be accessed through the deprecated property access
	 *
	 * @covers   Dazzle\Http\Response
	 */
	public function testReadResponseHeaders()
	{
		$this->assertSame(
			['Location' => ['https://example.com']],
			(new Response('php://memory', 200, ['Location' => ['https://example.com']]))->headers
		);
	}

	/**
	 * @testdox  Reading an unknown property generates an error
	 *
	 * @covers   Dazzle\Http\Response
	 */
	public function testReadUnknownProperty()
	{
		$this->expectNotice();

		(new Response('php://memory', 200, ['Location' => ['https://example.com']]))->foo;
	}
}
