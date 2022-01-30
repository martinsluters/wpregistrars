<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

interface DefaultArgumentableRegistrableFactoryInterface {

	/**
	 * Sets default arguments for something.
	 *
	 * @param array $default_arguments Default arguments.
	 * @return RegistrableFactoryInterface
	 */
	public function withDefaultRegistrationArguments( array $default_arguments ): RegistrableFactoryInterface;
}
