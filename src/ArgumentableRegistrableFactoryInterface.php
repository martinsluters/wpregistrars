<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

interface ArgumentableRegistrableFactoryInterface {

	/**
	 * Sets arguments for something.
	 *
	 * @param array $arguments Arguments.
	 * @return RegistrableFactoryInterface
	 */
	public function withArguments( array $arguments ): RegistrableFactoryInterface;
}
