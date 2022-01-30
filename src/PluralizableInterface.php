<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

/**
 * Interface that allows to use use Pluralizer implementations
 */
interface PluralizableInterface {

	/**
	 * Inject Pluralizer.
	 *
	 * @param PluralizerInterface $pluralizer Pluralizer implementation.
	 * @return void
	 */
	public function setPluralizer( PluralizerInterface $pluralizer ): void;

	/**
	 * Getter of Pluralizer implementation
	 *
	 * @return PluralizerInterface
	 */
	public function getPluralizer(): PluralizerInterface;

	/**
	 * Check if Pluralizer is available
	 *
	 * @return boolean
	 */
	public function isPluralizerAvailable(): bool;
}
