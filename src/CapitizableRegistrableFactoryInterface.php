<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

interface CapitizableRegistrableFactoryInterface {

	/**
	 * Can turn on/off auto capitalize something.
	 *
	 * @param boolean $should_auto_capitalize Toggle auto capitalize.
	 * @return RegistrableFactoryInterface
	 */
	public function shouldAutoCapitalize( bool $should_auto_capitalize ): RegistrableFactoryInterface;
}
