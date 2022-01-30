<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

interface RegistrableInterface {

	/**
	 * Main method to register something.
	 */
	public function register();
}
