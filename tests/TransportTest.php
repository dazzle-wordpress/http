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

use Dazzle\Http\Transport\Curl;
use Dazzle\Http\Transport\Socket;
use Dazzle\Http\Transport\Stream;
use Dazzle\Http\TransportInterface;
use Dazzle\Uri\Uri;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Dazzle\Http\TransportInterface instances.
 *
 * @since  1.0
 */
class TransportTest extends TestCase
{
	/**
	 * Options for the Transport object.
	 *
	 * @var  array
	 */
	protected $options = [
		'transport.curl'   => [CURLOPT_SSL_VERIFYPEER => false],
		'transport.socket' => ['X-WordPress-Test: true'],
		'transport.stream' => ['ignore_errors' => true],
	];

	/**
	 * The URL string for the HTTP stub.
	 *
	 * @var  string
	 */
	protected $stubUrl;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	protected function setUp(): void
	{
		parent::setUp();

		if (!\defined('JTEST_HTTP_STUB') && getenv('JTEST_HTTP_STUB') == '')
		{
			$this->markTestSkipped('The Transport test stub has not been configured');
		}

		$this->stubUrl = \defined('JTEST_HTTP_STUB') ? JTEST_HTTP_STUB : getenv('JTEST_HTTP_STUB');
	}

	/**
	 * Data provider for the request test methods.
	 *
	 * @return  \Generator
	 */
	public function transportProvider(): \Generator
	{
		yield 'curl adapter'   => [Curl::class];
		yield 'socket adapter' => [Socket::class];
		yield 'stream adapter' => [Stream::class];
	}

	/**
	 * @testdox  A transport can only be created with an appropriate data type for the options
	 *
	 * @param    string  $transportClass  The transport class to test
	 *
	 * @covers   Dazzle\Http\Transport\Curl
	 * @covers   Dazzle\Http\Transport\Socket
	 * @covers   Dazzle\Http\Transport\Stream
	 * @uses     Dazzle\Http\AbstractTransport
	 *
	 * @dataProvider  transportProvider
	 */
	public function testConstructorWithBadDataObject(string $transportClass)
	{
		if (!$transportClass::isSupported())
		{
			$this->markTestSkipped(sprintf('The "%s" adapter is not supported in this environment.', $transportClass));
		}

		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('The options param must be an array or implement the ArrayAccess interface.');

		new $transportClass(new \stdClass);
	}

	/**
	 * @testdox  A transport can make a GET request
	 *
	 * @param    string  $transportClass  The transport class to test
	 *
	 * @covers   Dazzle\Http\Transport\Curl
	 * @covers   Dazzle\Http\Transport\Socket
	 * @covers   Dazzle\Http\Transport\Stream
	 * @uses     Dazzle\Http\AbstractTransport
	 *
	 * @dataProvider  transportProvider
	 */
	public function testRequestGet(string $transportClass)
	{
		if (!$transportClass::isSupported())
		{
			$this->markTestSkipped(sprintf('The "%s" adapter is not supported in this environment.', $transportClass));
		}

		/** @var TransportInterface $transport */
		$transport = new $transportClass($this->options);

		$response = $transport->request('GET', new Uri($this->stubUrl));

		$body = json_decode((string) $response->getBody());

		$this->assertSame(
			200,
			$response->getStatusCode()
		);

		$this->assertSame(
			'GET',
			$body->method
		);
	}

	/**
	 * @testdox  A transport fails to make a GET request to an invalid domain
	 *
	 * @param    string  $transportClass  The transport class to test
	 *
	 * @covers   Dazzle\Http\Transport\Curl
	 * @covers   Dazzle\Http\Transport\Socket
	 * @covers   Dazzle\Http\Transport\Stream
	 * @uses     Dazzle\Http\AbstractTransport
	 *
	 * @dataProvider  transportProvider
	 */
	public function testBadDomainRequestGet(string $transportClass)
	{
		if (!$transportClass::isSupported())
		{
			$this->markTestSkipped(sprintf('The "%s" adapter is not supported in this environment.', $transportClass));
		}

		$this->expectException(\RuntimeException::class);

		/** @var TransportInterface $transport */
		$transport = new $transportClass($this->options);

		$response = $transport->request('GET', new Uri('https://xommunity.joomla.org'));
	}

	/**
	 * @testdox  A transport fails to make a GET request to an invalid URL
	 *
	 * @param    string  $transportClass  The transport class to test
	 *
	 * @covers   Dazzle\Http\Transport\Curl
	 * @covers   Dazzle\Http\Transport\Socket
	 * @covers   Dazzle\Http\Transport\Stream
	 * @uses     Dazzle\Http\AbstractTransport
	 *
	 * @dataProvider  transportProvider
	 */
	public function testRequestGet404(string $transportClass)
	{
		if (!$transportClass::isSupported())
		{
			$this->markTestSkipped(sprintf('The "%s" adapter is not supported in this environment.', $transportClass));
		}

		/** @var TransportInterface $transport */
		$transport = new $transportClass($this->options);

		$response = $transport->request('GET', new Uri(str_replace('.php', '.html', $this->stubUrl)));

		$this->assertSame(
			404,
			$response->getStatusCode()
		);
	}

	/**
	 * @testdox  A transport can make a GET request
	 *
	 * @param    string  $transportClass  The transport class to test
	 *
	 * @covers   Dazzle\Http\Transport\Curl
	 * @covers   Dazzle\Http\Transport\Socket
	 * @covers   Dazzle\Http\Transport\Stream
	 * @uses     Dazzle\Http\AbstractTransport
	 *
	 * @dataProvider  transportProvider
	 */
	public function testRequestPut(string $transportClass)
	{
		if (!$transportClass::isSupported())
		{
			$this->markTestSkipped(sprintf('The "%s" adapter is not supported in this environment.', $transportClass));
		}

		/** @var TransportInterface $transport */
		$transport = new $transportClass($this->options);

		$response = $transport->request('PUT', new Uri($this->stubUrl));

		$body = json_decode((string) $response->getBody());

		$this->assertSame(
			200,
			$response->getStatusCode()
		);

		$this->assertSame(
			'PUT',
			$body->method
		);
	}

	/**
	 * @testdox  A transport can make a GET request with basic authentication
	 *
	 * @param    string  $transportClass  The transport class to test
	 *
	 * @covers   Dazzle\Http\Transport\Curl
	 * @covers   Dazzle\Http\Transport\Socket
	 * @covers   Dazzle\Http\Transport\Stream
	 * @uses     Dazzle\Http\AbstractTransport
	 *
	 * @dataProvider  transportProvider
	 */
	public function testRequestCredentials(string $transportClass)
	{
		if (!$transportClass::isSupported())
		{
			$this->markTestSkipped(sprintf('The "%s" adapter is not supported in this environment.', $transportClass));
		}

		/** @var TransportInterface $transport */
		$transport = new $transportClass($this->options);

		$uri = new Uri($this->stubUrl);
		$uri->setUser('username');
		$uri->setPass('password');

		$response = $transport->request('GET', $uri);

		$body = json_decode((string) $response->getBody());

		$this->assertSame(
			200,
			$response->getStatusCode()
		);

		$this->assertSame(
			'username',
			$body->username
		);

		$this->assertSame(
			'password',
			$body->password
		);
	}

	/**
	 * @testdox  A transport can make a POST request with an array as the request data
	 *
	 * @param    string  $transportClass  The transport class to test
	 *
	 * @covers   Dazzle\Http\Transport\Curl
	 * @covers   Dazzle\Http\Transport\Socket
	 * @covers   Dazzle\Http\Transport\Stream
	 * @uses     Dazzle\Http\AbstractTransport
	 *
	 * @dataProvider  transportProvider
	 */
	public function testRequestPost(string $transportClass)
	{
		if (!$transportClass::isSupported())
		{
			$this->markTestSkipped(sprintf('The "%s" adapter is not supported in this environment.', $transportClass));
		}

		/** @var TransportInterface $transport */
		$transport = new $transportClass($this->options);

		$response = $transport->request('POST', new Uri($this->stubUrl . '?test=okay'), ['key' => 'value']);

		$body = json_decode((string) $response->getBody());

		$this->assertSame(
			200,
			$response->getStatusCode()
		);

		$this->assertSame(
			'POST',
			$body->method
		);

		$this->assertSame(
			'value',
			$body->post->key
		);
	}

	/**
	 * @testdox  A transport can make a POST request with a scalar value as the request data
	 *
	 * @param    string  $transportClass  The transport class to test
	 *
	 * @covers   Dazzle\Http\Transport\Curl
	 * @covers   Dazzle\Http\Transport\Socket
	 * @covers   Dazzle\Http\Transport\Stream
	 * @uses     Dazzle\Http\AbstractTransport
	 *
	 * @dataProvider  transportProvider
	 */
	public function testRequestPostScalar(string $transportClass)
	{
		if (!$transportClass::isSupported())
		{
			$this->markTestSkipped(sprintf('The "%s" adapter is not supported in this environment.', $transportClass));
		}

		/** @var TransportInterface $transport */
		$transport = new $transportClass($this->options);

		$response = $transport->request('post', new Uri($this->stubUrl . '?test=okay'), 'key=value');

		$body = json_decode((string) $response->getBody());

		$this->assertSame(
			200,
			$response->getStatusCode()
		);

		$this->assertSame(
			'POST',
			$body->method
		);

		$this->assertSame(
			'value',
			$body->post->key
		);
	}
}
