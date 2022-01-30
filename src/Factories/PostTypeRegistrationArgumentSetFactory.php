<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Factories;

use martinsluters\WPRegistrars\ArgumentSets\PostTypeRegistrationArgumentSet;
use martinsluters\WPRegistrars\Factories\AbstractWPTaxonomyPostTypeArgumentSetFactory;

/**
 * Factory class of PostTypeRegistrationArgumentSet
 */
class PostTypeRegistrationArgumentSetFactory extends AbstractWPTaxonomyPostTypeArgumentSetFactory {

	/**
	 * ArgumentSet of Post Type registration
	 *
	 * @var PostTypeRegistrationArgumentSet
	 */
	protected PostTypeRegistrationArgumentSet $argument_set;

	/**
	 * Creates ArgumentSet of post type registration.
	 *
	 * @return void
	 */
	protected function createArgumentSet(): void {
		$this->argument_set = new PostTypeRegistrationArgumentSet( $this->key, $this->registration_arguments );
	}

	/**
	 * Builder method implementation for post type registration arguments.
	 *
	 * @param string $key Post Type registration key a.k.a post type key.
	 * @param array  $registration_arguments Array of arguments for registering a post type.
	 * @return PostTypeRegistrationArgumentSetFactory
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/
	 */
	public function withArguments( string $key, array $registration_arguments ): self {
		$this->key = $key;
		$this->registration_arguments = $registration_arguments;
		return $this;
	}
}
