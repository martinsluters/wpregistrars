<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\ArgumentSets;

use martinsluters\WPRegistrars\Support\Arguments;
use martinsluters\WPRegistrars\ArgumentSetInterface;
use martinsluters\WPRegistrars\PluralizableInterface;
use martinsluters\WPRegistrars\CapitalizableInterface;
use martinsluters\WPRegistrars\CapitalizableTrait;
use martinsluters\WPRegistrars\PluralizableTrait;

/**
 * ArgumentSet abstract class for registering WordPress Taxonomies and Post Types
 */
abstract class AbstractWPTaxonomyPostTypeArgumentSet implements ArgumentSetInterface, PluralizableInterface, CapitalizableInterface {

	use Arguments;
	use CapitalizableTrait;
	use PluralizableTrait;

	/**
	 * Key
	 *
	 * @var string
	 */
	protected string $key;

	/**
	 * Arguments
	 *
	 * @var array
	 */
	protected array $arguments;

	/**
	 * Default registration arguments
	 *
	 * @var array
	 */
	protected array $default_arguments = [];

	/**
	 * Check if arguments contains an element keyed with 'label'
	 *
	 * @return bool
	 */
	protected function isLabelInRawArguments(): bool {
		return \array_key_exists( 'label', $this->arguments );
	}

	/**
	 * Check if default arguments contains an element keyed with 'label'
	 *
	 * @return bool
	 */
	protected function isLabelInRawDefaultArguments(): bool {
		return \array_key_exists( 'label', $this->default_arguments );
	}

	/**
	 * Getter of key
	 *
	 * @return string
	 */
	public function getKey(): string {
		return $this->key;
	}

	/**
	 * Getter of arguments
	 *
	 * @return array
	 */
	public function getArguments(): array {
		$arguments = $this->parseArgs( $this->arguments, $this->getDefaultArguments() );

		if ( $this->isLabelInRawArguments() || $this->isLabelInRawDefaultArguments() ) {
			return $arguments;
		}

		$arguments['label'] = $this->parseLabelFromKey( $this->getKey() );

		if ( $this->getDoAutoCapitalize() ) {
			$arguments['label'] = ucwords( $arguments['label'] );
		}

		if ( $this->isPluralizerAvailable() ) {
			$arguments['label'] = $this->getPluralizer()->pluralize( $arguments['label'] );
		}

		return $arguments;
	}

	/**
	 * Set default arguments
	 *
	 * @param array $new_default_arguments Array containing new default arguments.
	 * @return void
	 */
	public function setDefaultArguments( array $new_default_arguments ): void {
		$this->default_arguments = $new_default_arguments;
	}

	/**
	 * Returns default arguments
	 *
	 * @return array
	 */
	public function getDefaultArguments(): array {
		return $this->default_arguments;
	}
}
