<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Registrars;

/**
 * Implementation of bulk registering post types
 */
class BulkRegistrarPostTypes extends AbstractBulkRegistrar {

	/**
	 * Main method of registering single post type.
	 *
	 * @return mixed Returns WP_Post_Type|WP_Error|(bool) false.
	 */
	protected function registerSingle() { // phpcs:ignore NeutronStandard.Functions.TypeHint.NoReturnType
		return register_post_type(
			apply_filters( 'wpregistrars/bulk_registrar/post_type/key', $this->getCurrentArgumentSet()->getKey(), $this->getCurrentArgumentSet()->getArguments() ),
			apply_filters( 'wpregistrars/bulk_registrar/post_type/args', $this->getCurrentArgumentSet()->getArguments(), $this->getCurrentArgumentSet()->getKey() )
		);
	}
}
