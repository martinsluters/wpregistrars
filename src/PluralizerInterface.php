<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

/**
 * Interface of Pluralizer
 */
interface PluralizerInterface {

	/**
	 * Abstraction of a method to convert a word from singular to plural
	 *
	 * @param string $singular_string A word to be converted to plural form.
	 */
	public function pluralize( string $singular_string ): string;
}
