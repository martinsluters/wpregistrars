<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

/**
 * Interface that allows to toggle flag of capitalization of something
 */
interface CapitalizableInterface {

	/**
	 * Abstraction of setter method of flag
	 *
	 * @param bool $should_auto_capitalize New value of flag.
	 */
	public function setDoAutoCapitalize( bool $should_auto_capitalize ): void;

	/**
	 * Abstraction of getter method of flag
	 *
	 * @return boolean
	 */
	public function getDoAutoCapitalize(): bool;
}
