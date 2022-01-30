<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

/**
 * ArgumentSet abstract class
 */
interface ArgumentSetInterface {

	/**
	 * Validity of argument set
	 *
	 * @return boolean
	 */
	public function isArgumentSetValid(): bool;
}
