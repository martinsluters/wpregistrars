<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Factories;

use martinsluters\WPRegistrars\DefaultArgumentableRegistrableFactoryInterface;
use martinsluters\WPRegistrars\ArgumentableRegistrableFactoryInterface;
use martinsluters\WPRegistrars\PluralizableRegistrableFactoryInterface;
use martinsluters\WPRegistrars\CapitizableRegistrableFactoryInterface;
use martinsluters\WPRegistrars\Registrars\AbstractBulkRegistrar;
use martinsluters\WPRegistrars\Exceptions\WPRegistrarsException;
use martinsluters\WPRegistrars\ArgumentSetContainerInterface;
use martinsluters\WPRegistrars\RegistrableFactoryInterface;
use martinsluters\WPRegistrars\PluralizerInterface;
use Doctrine\Inflector\Inflector;

/**
 * ArgumentSet abstract class for registering WordPress Taxonomies and Post Types
 */
abstract class AbstractWPTaxonomyPostTypeBulkRegistrarFactory implements PluralizableRegistrableFactoryInterface, CapitizableRegistrableFactoryInterface, DefaultArgumentableRegistrableFactoryInterface, ArgumentableRegistrableFactoryInterface, RegistrableFactoryInterface {

	/**
	 * Array of arguments passed to factory
	 *
	 * @var array
	 */
	protected array $raw_arguments;

	/**
	 * Argument set container
	 *
	 * @var \martinsluters\WPRegistrars\ArgumentSetContainerInterface
	 */
	protected ArgumentSetContainerInterface $argument_set_container;

	/**
	 * Builder method implementation for registrable arguments
	 *
	 * @param array $arguments Arguments to use for registration.
	 * @return self
	 */
	public function withArguments( array $arguments ): self {
		$this->raw_arguments = $arguments;
		$this->validateRawArguments();
		return $this;
	}

	/**
	 * Builder method implementation for default registrable arguments
	 *
	 * @param array $default_arguments Default arguments to use for registration.
	 * @return self
	 */
	public function withDefaultRegistrationArguments( array $default_arguments ): self {
		$this->argument_set_factory->withDefaultRegistrationArguments( $default_arguments );
		return $this;
	}

	/**
	 * Builder method implementation for pluralizer to be used.
	 *
	 * @param \martinsluters\WPRegistrars\PluralizerInterface $pluralizer Pluralizer instance.
	 * @return self
	 */
	public function withPluralizer( PluralizerInterface $pluralizer ): self {
		$this->argument_set_factory->withPluralizer( $pluralizer );
		return $this;
	}

	/**
	 * Builder method implementation for a flag to on/off auto pluralize labels.
	 *
	 * @param boolean $should_auto_pluralize_label Toggle auto pluralize labels.
	 * @return self
	 */
	public function shouldAutoPluralize( bool $should_auto_pluralize_label ): self {
		$this->argument_set_factory->shouldAutoPluralize( $should_auto_pluralize_label );
		return $this;
	}

	/**
	 * Builder method implementation for a flag to on/off auto capitalize labels.
	 *
	 * @param boolean $should_auto_capitalize_label Toggle auto capitalize labels.
	 * @return self
	 */
	public function shouldAutoCapitalize( bool $should_auto_capitalize_label ): self {
		$this->argument_set_factory->shouldAutoCapitalize( $should_auto_capitalize_label );
		return $this;
	}

	/**
	 * Getter of argument set container.
	 *
	 * @return ArgumentSetContainerInterface
	 */
	public function testGetArgumentSetContainer(): ArgumentSetContainerInterface {
		return $this->argument_set_container;
	}

	/**
	 * Main builder method implementation that attempts to create a bulk registrar instance.
	 *
	 * @return AbstractBulkRegistrar
	 * @throws WPRegistrarsException If argument not provided or they are in incorrect format.
	 */
	public function create(): AbstractBulkRegistrar {
		$this->validateRawArguments();
		$this->fillArgumentSetContainer();
		return $this->createBulkRegistrar();
	}

	/**
	 * Validate arguments passed to factory.
	 *
	 * @return void
	 * @throws WPRegistrarsException If argument not provided or they are in incorrect format.
	 */
	private function validateRawArguments(): void {
		if ( $this->isNotSetRawArgumentList() ) {
			throw new WPRegistrarsException( 'Bulk registrar factory arguments not provided (' . self::class . ')' );
		}

		if ( $this->isInvalidRawArgumentList() ) {
			throw new WPRegistrarsException( 'Bulk registrar factory invalid format of arguments provided (' . self::class . ')' );
		}
	}

	/**
	 * Checks if argument list passed to builder is valid.
	 *
	 * @return boolean True/yes, false/no.
	 */
	private function isInvalidRawArgumentList(): bool {
		if ( $this->containsEmptyArguments() ) {
			return true;
		}
		$has_invalid_array_element = false;
		foreach ( $this->raw_arguments as $key => $value ) {
			if (
				( \is_int( $key ) && ! \is_string( $value ) ) ||
				( \is_string( $key ) && ! \is_array( $value ) )
			) {
				$has_invalid_array_element = true;
				break;
			}
		}
		return $has_invalid_array_element;
	}

	/**
	 * Checks if argument list passed to builder is empty.
	 *
	 * @return bool True/yes, is empty, false/no, is not empty.
	 */
	private function containsEmptyArguments(): bool {
		return 0 === \count( $this->raw_arguments );
	}

	/**
	 * Checks if argument list is not passed to builder.
	 *
	 * @return bool True/yes, is not passed, false/no, is passed.
	 */
	private function isNotSetRawArgumentList(): bool {
		return ! isset( $this->raw_arguments );
	}

	/**
	 * Abstract method to fill argument set container.
	 *
	 * @return void
	 */
	abstract protected function fillArgumentSetContainer(): void;

	/**
	 * Abstract method to to create bulk registrar.
	 *
	 * @return AbstractBulkRegistrar
	 */
	abstract protected function createBulkRegistrar(): AbstractBulkRegistrar;
}
