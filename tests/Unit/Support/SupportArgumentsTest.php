<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Support;

use martinsluters\WPRegistrars\Support\Arguments;
use martinsluters\WPRegistrars\Tests\Unit\TestCase;

/**
 * Tests of Arguments Supporting methods.
 */
class SupportArgumentsTest extends TestCase {

	use Arguments;

	/**
	 * Test label is created from various format keys
	 *
	 * @dataProvider prepareLabelFromKeyProvider
	 * @param string $key from which to create label.
	 * @param string $expected label made from key.
	 * @return void
	 */
	public function testPrepareLabelFromKey( string $key, string $expected ): void {
		$this->assertSame( $expected, $this->parseLabelFromKey( $key ) );
	}

	/**
	 * Test arrays arguments are parsed as expected
	 * Almost the same as wp_parse_args however it does deep recursive merge.
	 *
	 * @dataProvider parseArgsProvider
	 * @param array $args array of arguments.
	 * @param array $default_args array of arguments.
	 * @param array $expected parsed array of arguments.
	 * @return void
	 */
	public function testParseArgs( array $args, array $default_args, array $expected ): void {
		$this->assertEqualsCanonicalizing( $expected, $this->parseArgs( $args, $default_args ) );
	}

	/**
	 * Data provider to test parseLabelFromKey
	 *
	 * @return array
	 */
	public function prepareLabelFromKeyProvider(): array {
		// key, expected.
		return [
			'must convert underscore to space with camelcase' =>
			[
				'foo_bar',
				'foo bar',
			],
			'must convert hyphen to space' =>
			[
				'foo-bar',
				'foo bar',
			],
			'must maintain letter case' =>
			[
				'FOO_BAR',
				'FOO BAR',
			],
		];
	}

	/**
	 * Data provider to test parseArgs
	 *
	 * @return array
	 */
	public function parseArgsProvider(): array {
		// extra args, default args, expected.
		return [
			'extra args must take over default args if 1 dimension array' =>
			[
				[ 'foo' => 1 ],
				[ 'foo' => 2 ],
				[ 'foo' => 1 ],
			],
			'extra arg must take over default args if multi dimension array' =>
			[
				[ 'foo' => [ 'bar' => 1 ] ],
				[ 'foo' => [ 'bar' => 2 ] ],
				[ 'foo' => [ 'bar' => 1 ] ],
			],

			'must merge both multi dimension array args (extra and default) and does not remove elements in default args' =>
			[
				[ 'foobar' => [ 'foo' => 'bar' ] ],
				[ 'foobar' => [ 'jane' => 'doe' ] ],
				[
					'foobar' =>
					[
						'foo' => 'bar',
						'jane' => 'doe',
					],
				],
			],

			'must merge args (extra and default) if one is 1D and second 2D array' =>
			[
				[ 'bazbar' => 'baz' ],
				[ 'foobar' => [ 'jane' => 'doe' ] ],
				[
					'bazbar' => 'baz',
					'foobar' => [ 'jane' => 'doe' ],
				],
			],
		];
	}
}
