<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Support;

trait Arguments {

	/**
	 * Parse label from key
	 *
	 * @param  string $key String that is a key in WordPress context.
	 * @return string Prepared label.
	 */
	public function parseLabelFromKey( string $key ): string {
		return preg_replace( [ '/-/', '/_/' ], ' ', $key );
	}

	/**
	 * Almost the same as wp_parse_args however it does deep recursive merge.
	 *
	 * @param  array $args Array to merge.
	 * @param  array $default_args  array to merge.
	 * @return array
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_parse_args/
	 */
	public function parseArgs( array $args, array $default_args ): array {
		$result = $default_args;

		if ( empty( $args ) ) {
			return $result;
		}

		foreach ( $args as $k => $v ) {
			if ( \is_array( $v ) && isset( $result[ $k ] ) ) {
				$result[ $k ] = self::parseArgs( $v, $result[ $k ] );
			} else {
				$result[ $k ] = $v;
			}
		}
		return $result;
	}
}
