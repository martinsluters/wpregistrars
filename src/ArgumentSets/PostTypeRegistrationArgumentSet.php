<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\ArgumentSets;

/**
 * Implementation of post type registration class
 */
class PostTypeRegistrationArgumentSet extends AbstractWPTaxonomyPostTypeArgumentSet {

	/**
	 * Constructor of class
	 *
	 * @param string $post_type_key Post type key.
	 * @param array  $post_type_arguments Post type arguments.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/
	 */
	public function __construct( string $post_type_key, array $post_type_arguments ) {
		$this->key = $post_type_key;
		$this->arguments = $post_type_arguments;
	}

	/**
	 * Returns default post type registration arguments
	 *
	 * @return array
	 */
	public function getDefaultArguments(): array {
		return apply_filters( 'wpregistrars/post_type/default_arguments', $this->default_arguments, $this->getKey() );
	}

	/**
	 * Validity of post type registration argument set
	 *
	 * @return boolean
	 */
	public function isArgumentSetValid(): bool {
		return (
			\is_string( $this->getKey() ) &&
			\is_array( $this->getArguments() )
		);
	}
}
