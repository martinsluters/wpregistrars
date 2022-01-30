<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\ArgumentSets;

/**
 * Implementation of post type registration class
 */
class TaxonomyRegistrationArgumentSet extends AbstractWPTaxonomyPostTypeArgumentSet {

	/**
	 * Object types
	 *
	 * @var array|null
	 */
	protected ?array $object_type = null;

	/**
	 * Tells if default object type (user provided as default for all taxonomies)
	 * can be used when registering.
	 *
	 * @param array|null $original_object_type $object_type value per taxonomy that user provided or null if not specified.
	 * @param array      $default_arguments default registration arguments user provided.
	 * @return boolean
	 */
	private function shouldUseDefaultObjectType( ?array $original_object_type, array $default_arguments ): bool {
		return \array_key_exists( 'object_type', $default_arguments ) &&
		\is_array( $default_arguments['object_type'] ) &&
		\is_null( $original_object_type );
	}

	/**
	 * Constructor of class
	 *
	 * @param string     $taxonomy_key Post type key.
	 * @param array|null $object_type Object type(s) [string] or null if not specified.
	 * @param array      $taxonomy_registration_arguments Post type arguments.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
	 */
	public function __construct( string $taxonomy_key, ?array $object_type, array $taxonomy_registration_arguments ) {
		$this->key = $taxonomy_key;
		$this->object_type = $object_type;
		$this->arguments = $taxonomy_registration_arguments;
	}

	/**
	 * Returns default post type registration arguments
	 *
	 * @return array
	 */
	public function getDefaultArguments(): array {
		return apply_filters( 'wpregistrars/taxonomy/default_arguments', $this->default_arguments, $this->getKey() );
	}

	/**
	 * Getter of taxonomy registration object type(s).
	 *
	 * @return array
	 */
	public function getObjectType(): array {
		$default_arguments = $this->getDefaultArguments();

		if ( $this->shouldUseDefaultObjectType( $this->object_type, $default_arguments ) ) {
			return $default_arguments['object_type'];
		}

		return $this->object_type ?? [];
	}

	/**
	 * Validity of post type registration argument set
	 *
	 * @return boolean
	 */
	public function isArgumentSetValid(): bool {
		return (
			\is_string( $this->getKey() ) &&
			\is_array( $this->getObjectType() ) &&
			\is_array( $this->getArguments() )
		);
	}

}
