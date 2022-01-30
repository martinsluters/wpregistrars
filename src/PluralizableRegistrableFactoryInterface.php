<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

interface PluralizableRegistrableFactoryInterface {

	/**
	 * Sets Pluralizer implementation for pluralization
	 *
	 * @param PluralizerInterface $pluralizer Pluralizer implementation.
	 * @return RegistrableFactoryInterface
	 */
	public function withPluralizer( PluralizerInterface $pluralizer ): RegistrableFactoryInterface;

	/**
	 * Can turn on/off any auto pluralization of something
	 *
	 * @param boolean $should_auto_pluralize Should pluralize.
	 * @return RegistrableFactoryInterface
	 */
	public function shouldAutoPluralize( bool $should_auto_pluralize ): RegistrableFactoryInterface;
}
