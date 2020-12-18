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

use Dazzle\Http\Http;
use Dazzle\Http\HttpFactory;
use Dazzle\Http\TransportInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Dazzle\Http\HttpFactory.
 */
class HttpFactoryTest extends TestCase
{
	/**
	 * Object being tested
	 *
	 * @var  HttpFactory
	 */
	private $instance;

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * @return  void
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->instance = new HttpFactory;
	}

	/**
	 * @testdox  A HTTP client can be created
	 *
	 * @covers   Dazzle\Http\HttpFactory
	 * @uses     Dazzle\Http\AbstractTransport
	 * @uses     Dazzle\Http\Http
	 * @uses     Dazzle\Http\Transport\Curl
	 */
	public function testGetHttp()
	{
		$this->assertInstanceOf(
			Http::class,
			$this->instance->getHttp()
		);
	}

	/**
	 * @testdox  A HTTP client can only be created with an appropriate options data type
	 *
	 * @covers   Dazzle\Http\HttpFactory
	 */
	public function testGetHttpDisallowsNonArrayObjects()
	{
		$this->expectException(\InvalidArgumentException::class);

		$this->instance->getHttp(new \stdClass);
	}

	/**
	 * @testdox  A HTTP client cannot be created when no transport driver is available
	 *
	 * @covers  Dazzle\Http\HttpFactory
	 */
	public function testGetHttpException()
	{
		$this->expectException(\RuntimeException::class);

		$this->assertInstanceOf(
			Http::class,
			$this->instance->getHttp([], [])
		);
	}

	/**
	 * @testdox  A transport driver can be created
	 *
	 * @covers   Dazzle\Http\HttpFactory
	 * @uses     Dazzle\Http\AbstractTransport
	 * @uses     Dazzle\Http\Transport\Curl
	 */
	public function testGetAvailableDriver()
	{
		$this->assertInstanceOf(
			TransportInterface::class,
			$this->instance->getAvailableDriver([], null)
		);

		$this->assertFalse(
			$this->instance->getAvailableDriver([], []),
			'Passing an empty array should return false due to there being no adapters to test'
		);

		$this->assertFalse(
			$this->instance->getAvailableDriver([], ['fopen']),
			'A false should be returned if a class is not present or supported'
		);

		include_once __DIR__ . '/stubs/DummyTransport.php';

		$this->assertFalse(
			$this->instance->getAvailableDriver([], ['DummyTransport']),
			'Passing an empty array should return false due to there being no adapters to test'
		);
	}

	/**
	 * @testdox  A driver can only be created with an appropriate options data type
	 *
	 * @covers   Dazzle\Http\HttpFactory
	 */
	public function testGetAvailableDriverDisallowsNonArrayObjects()
	{
		$this->expectException(\InvalidArgumentException::class);

		$this->instance->getAvailableDriver(new \stdClass);
	}

	/**
	 * @testdox  The list of transport drivers is returned
	 *
	 * @covers   Dazzle\Http\HttpFactory
	 */
	public function testGetHttpTransports()
	{
		$transports = ['Stream', 'Socket', 'Curl'];
		sort($transports);

		$this->assertEquals(
			$transports,
			$this->instance->getHttpTransports()
		);
	}
}
