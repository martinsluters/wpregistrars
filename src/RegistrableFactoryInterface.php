<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

interface RegistrableFactoryInterface {

	/**
	 * Main method of creating registrar
	 */
	public function create();
}
