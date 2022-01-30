<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Factories;

use martinsluters\WPRegistrars\Exceptions\WPRegistrarsException;
use martinsluters\WPRegistrars\DefaultArgumentableRegistrableFactoryInterface;
use martinsluters\WPRegistrars\PluralizableRegistrableFactoryInterface;
use martinsluters\WPRegistrars\PluralizerDoctrineInflectorAdapter;
use martinsluters\WPRegistrars\RegistrableFactoryInterface;
use martinsluters\WPRegistrars\ArgumentSetInterface;
use martinsluters\WPRegistrars\PluralizerInterface;
use martinsluters\WPRegistrars\PluralizerNull;
use Doctrine\Inflector\{ InflectorFactory, Inflector, Language };

/**
 * ArgumentSet abstract class for registering WordPress Taxonomies and Post Types
 */
abstract class AbstractWPTaxonomyPostTypeArgumentSetFactory implements PluralizableRegistrableFactoryInterface, DefaultArgumentableRegistrableFactoryInterface, RegistrableFactoryInterface {

	/**
	 * Argument Set container
	 *
	 * @var ArgumentSetContainerInterface
	 */
	private ArgumentSetContainerInterface $argument_set_container;

	/**
	 * Auto capitalize label flag
	 *
	 * @var boolean
	 */
	private bool $should_auto_capitalize_label;

	/**
	 * Stores default registration arguments of a registrable
	 *
	 * @var array
	 */
	private array $default_registration_arguments;

	/**
	 * Pluralizer to be used when creating an argument set
	 *
	 * @var PluralizerInterface
	 */
	private PluralizerInterface $pluralizer;

	/**
	 * Stores initial or user defined Pluralizer
	 * Acts as a backup when toggling ::shouldAutoPluralize
	 *
	 * @var PluralizerInterface
	 */
	private PluralizerInterface $base_pluralizer;

	/**
	 * Doctrine\Inflector instance
	 *
	 * @var \Doctrine\Inflector\Inflector
	 */
	private Inflector $doctrine_inflector;

	/**
	 * Key of a registrable
	 *
	 * @var string
	 */
	protected string $key;

	/**
	 * Registration arguments of a registrable
	 *
	 * @var array
	 */
	protected array $registration_arguments;

	/**
	 * Manipulates ArgumentSet components
	 *
	 * @return void
	 */
	protected function configureArgumentSet(): void {
		$this->argument_set->setPluralizer( $this->pluralizer );

		if ( isset( $this->should_auto_capitalize_label ) ) {
			$this->argument_set->setDoAutoCapitalize( $this->should_auto_capitalize_label );
		}

		if ( isset( $this->default_registration_arguments ) ) {
			$this->argument_set->setDefaultArguments( $this->default_registration_arguments );
		}
	}

	/**
	 * Validates necessities before factory builds.
	 *
	 * @return void
	 * @throws WPRegistrarsException If try to build argument set without using arguments.
	 */
	protected function validateBuildOperation() {
		if ( ! isset( $this->key, $this->registration_arguments ) ) {
			throw new WPRegistrarsException( 'Argument set factory arguments not provided (' . self::class . ')' );
		}
	}

	/**
	 * Constructor of ArgumentSet Factory
	 */
	public function __construct() {
		$this->doctrine_inflector = ( new InflectorFactory( Language::ENGLISH ) )->create()->build();
		$this->pluralizer = new PluralizerDoctrineInflectorAdapter( $this->doctrine_inflector );
		$this->base_pluralizer = $this->pluralizer;
	}

	/**
	 * Build operation to set default registration arguments that wil be applied to
	 * all registrables.
	 *
	 * @param array $default_registration_arguments Default arguments to use.
	 * @return AbstractWPTaxonomyPostTypeArgumentSetFactory
	 */
	public function withDefaultRegistrationArguments( array $default_registration_arguments ): AbstractWPTaxonomyPostTypeArgumentSetFactory {
		$this->default_registration_arguments = $default_registration_arguments;
		return $this;
	}

	/**
	 * Build operation to set pluralizer that might be used for all registrables.
	 *
	 * @param \martinsluters\WPRegistrars\PluralizerInterface $pluralizer Concrete pluralizer.
	 * @return AbstractWPTaxonomyPostTypeArgumentSetFactory
	 */
	public function withPluralizer( PluralizerInterface $pluralizer ): AbstractWPTaxonomyPostTypeArgumentSetFactory {
		$this->pluralizer = $pluralizer;
		$this->base_pluralizer = $pluralizer;
		return $this;
	}

	/**
	 * Build operation that determines if Taxonomy or Post Type label should be
	 * auto pluralized.
	 *
	 * @param boolean $should_auto_pluralize_label Should auto pluralize label flag.
	 * @return AbstractWPTaxonomyPostTypeArgumentSetFactory
	 */
	public function shouldAutoPluralize( bool $should_auto_pluralize_label ): AbstractWPTaxonomyPostTypeArgumentSetFactory {
		$this->pluralizer = $should_auto_pluralize_label ? $this->base_pluralizer : new PluralizerNull();
		return $this;
	}

	/**
	 * Build operation that determines if Taxonomy or Post Type label should be
	 * auto capitalized.
	 *
	 * @param boolean $should_auto_capitalize_label Should auto capitalize label flag.
	 * @return AbstractWPTaxonomyPostTypeArgumentSetFactory
	 */
	public function shouldAutoCapitalize( bool $should_auto_capitalize_label ): AbstractWPTaxonomyPostTypeArgumentSetFactory {
		$this->should_auto_capitalize_label = $should_auto_capitalize_label;
		return $this;
	}

	/**
	 * Main factory template method to create new ArgumentSet
	 *
	 * @return ArgumentSetInterface A new ArgumentSet.
	 * @throws WPRegistrarsException If arguments not provided or do not validate.
	 */
	public function create(): ArgumentSetInterface {
		$this->validateBuildOperation();
		$this->createArgumentSet();
		$this->configureArgumentSet();
		return $this->argument_set;
	}

	/**
	 * Main method to create a concrete argument set.
	 */
	abstract protected function createArgumentSet();
}
