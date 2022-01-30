<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

use martinsluters\WPRegistrars\Exceptions\WPRegistrarsException;

trait PluralizableTrait {

	/**
	 * PluralizerInterface implementation
	 *
	 * @var PluralizerInterface
	 */
	protected PluralizerInterface $pluralizer;

	/**
	 * Setter of pluralizer
	 *
	 * @param PluralizerInterface $pluralizer Implementation of Pluralizable.
	 * @return void
	 */
	public function setPluralizer( PluralizerInterface $pluralizer ): void {
		$this->pluralizer = $pluralizer;
	}

	/**
	 * Getter of pluralizer
	 *
	 * @return PluralizerInterface
	 * @throws WPRegistrarsException If pluralizer not available.
	 */
	public function getPluralizer(): PluralizerInterface {
		if ( ! $this->isPluralizerAvailable() ) {
			throw new WPRegistrarsException( 'Pluralizer not available.' );
		}
		return $this->pluralizer;
	}

	/**
	 * Check if Pluralizer is available
	 *
	 * @return boolean
	 */
	public function isPluralizerAvailable(): bool {
		return isset( $this->pluralizer );
	}
}
