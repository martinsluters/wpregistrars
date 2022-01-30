<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Factories;

use martinsluters\WPRegistrars\ArgumentSets\TaxonomyRegistrationArgumentSet;
use martinsluters\WPRegistrars\Exceptions\WPRegistrarsException;

/**
 * Factory class of TaxonomyRegistrationArgumentSet
 */
class TaxonomyRegistrationArgumentSetFactory extends AbstractWPTaxonomyPostTypeArgumentSetFactory {

	/**
	 * ArgumentSet of Taxonomy registration
	 *
	 * @var TaxonomyRegistrationArgumentSet
	 */
	protected TaxonomyRegistrationArgumentSet $argument_set;

	/**
	 * Array of arguments for registering a taxonomy or null if not specified
	 *
	 * @var array|null
	 */
	private ?array $taxonomy_object_type;

	/**
	 * Builder method implementation for taxonomy registration arguments.
	 *
	 * @param string     $key Taxonomy registration key a.k.a taxonomy key.
	 * @param array|null $taxonomy_object_type Array of object types with which the taxonomy should be associated or null if not specified.
	 * @param array      $registration_arguments Array of arguments for registering a taxonomy.
	 * @return TaxonomyRegistrationArgumentSetFactory
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
	 */
	public function withArguments( string $key, ?array $taxonomy_object_type, array $registration_arguments ): self {
		$this->key = $key;
		$this->registration_arguments = $registration_arguments;
		$this->taxonomy_object_type = $taxonomy_object_type;
		return $this;
	}

	/**
	 * Creates ArgumentSet of taxonomy registration.
	 *
	 * @return void
	 */
	protected function createArgumentSet(): void {
		$this->argument_set = new TaxonomyRegistrationArgumentSet( $this->key, $this->taxonomy_object_type, $this->registration_arguments );
	}
}
