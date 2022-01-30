<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

use \Doctrine\Inflector\Inflector;

/**
 * Implementation (adapter) of Doctrine\Inflector's
 * pluralize method that makes word plural
 */
class PluralizerDoctrineInflectorAdapter implements PluralizerInterface {

	/**
	 * Instance of Inflector class
	 *
	 * @var Inflector
	 */
	private Inflector $inflector;

	/**
	 * Class constructor
	 *
	 * @param Inflector $inflector Inctance of Doctrine\Inflector\Inflector.
	 */
	public function __construct( Inflector $inflector ) {
		$this->inflector = $inflector;
	}

	/**
	 * Main method to convert a word from singular to plural
	 *
	 * @param string $singular_string A word to be converted to plural form.
	 */
	public function pluralize( string $singular_string ): string {
		return (string) $this->inflector->pluralize( $singular_string );
	}
}
