<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Registrars;

/**
 * Implementation of bulk registering taxonomies
 */
class BulkRegistrarTaxonomies extends AbstractBulkRegistrar {

	/**
	 * Main method of registering single taxonomy.
	 *
	 * @return mixed Returns WP_Post_Type|WP_Error|(bool) false.
	 */
	protected function registerSingle() { // phpcs:ignore NeutronStandard.Functions.TypeHint.NoReturnType
		return register_taxonomy(
			apply_filters( 'wpregistrars/bulk_registrar/taxonomy/key', $this->getCurrentArgumentSet()->getKey(), $this->getCurrentArgumentSet()->getObjectType(), $this->getCurrentArgumentSet()->getArguments() ),
			apply_filters( 'wpregistrars/bulk_registrar/taxonomy/object_type', $this->getCurrentArgumentSet()->getObjectType(), $this->getCurrentArgumentSet()->getKey(), $this->getCurrentArgumentSet()->getArguments() ),
			apply_filters( 'wpregistrars/bulk_registrar/taxonomy/args', $this->getCurrentArgumentSet()->getArguments(), $this->getCurrentArgumentSet()->getKey(), $this->getCurrentArgumentSet()->getObjectType() )
		);
	}
}
