<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Integration;

use martinsluters\WPRegistrars\Factories\{ BulkRegistrarPostTypesFactory, BulkRegistrarTaxonomiesFactory };
use martinsluters\WPRegistrars\PluralizerDoctrineInflectorAdapter;
use martinsluters\WPRegistrars\ArgumentSetContainerInterface;
use martinsluters\WPRegistrars\PluralizerInterface;
use Doctrine\Inflector\InflectorFactory;
use Yoast\WPTestUtils\WPIntegration\TestCase as WPIntegrationTestCase;

/**
 * Abstract base class of Post Type and Taxonomy bulk registrar factory tests
 */
abstract class TestCase extends WPIntegrationTestCase {

	use \FalseyAssertEqualsDetector\Test;

	/**
	 * Pluralizer instance
	 *
	 * @var PluralizerInterface
	 */
	protected PluralizerInterface $pluralizer;

	/**
	 * Bulk Registrar Post Types Factory instance
	 *
	 * @var BulkRegistrarPostTypesFactory
	 */
	protected BulkRegistrarPostTypesFactory $bulk_registrar_post_type_factory;

	/**
	 * Bulk Registrar Taxonomies Factory instance
	 *
	 * @var BulkRegistrarTaxonomiesFactory
	 */
	protected BulkRegistrarTaxonomiesFactory $bulk_registrar_taxonomy_factory;

	/**
	 * Array of post types attempted to register
	 *
	 * @var array
	 */
	protected array $post_types = [];

	/**
	 * Array of taxonomies attempted to register
	 *
	 * @var array
	 */
	protected array $taxonomies = [];

	/**
	 * Post types to be registered
	 */
	const POST_TYPE_REGISTRAR_TEST_CASES_CONFIGURATION = [
		'Test create post types by Indexed Array' => [
			'dwarf',
			'hobbit',
		],
		'Test create post types by Associative Array (Multidimensional)' => [
			'elve' => [ 'label' => 'List of Elves' ],
			'human' => [
				'label' => 'List of Humans',
				'show_in_rest' => false,
			],
		],
		'Test label gets pluralized / capitalized' => [
			'ork',
			'dwarf_mountain',
		],
		'Test label priority' => [
			'munition' => [ 'label' => 'Weapons' ],
		],
	];

	const TAXONOMY_REGISTRAR_TEST_CASES_CONFIGURATION = [
		'Test create taxonomies by Indexed Array' => [
			'region',
			'loot',
		],
		'Test create taxonomies by Associative Array (Multidimensional)' => [
			'distinction' => [
				'show_in_rest' => false,
				'object_type' => [
					'dwarf',
					'hobbit',
				],
			],
			'division' => [ 'label' => 'List of Divisions' ],
		],
		'Test label gets pluralized / capitalized' => [
			'class',
			'animal_family',
		],
		'Test label priority' => [
			'version' => [ 'label' => 'Releases' ],
		],
	];

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->setPluralizer();
		$this->bulk_registrar_post_type_factory = new BulkRegistrarPostTypesFactory();
		$this->bulk_registrar_taxonomy_factory = new BulkRegistrarTaxonomiesFactory();
		$this->register();
	}

	/**
	 * Unregister objects.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		$wp_post_types = get_post_types( [ '_builtin' => false ] );
		array_map(
			function( string $post_type ): void {
				unregister_post_type( $post_type );
			},
			$wp_post_types
		);

		$taxonomies = get_taxonomies( [ '_builtin' => false ] );
		array_map(
			function( string $taxonomy ): void {
				unregister_taxonomy( $taxonomy );
			},
			$taxonomies
		);

		parent::tearDown();
	}

	/**
	 * Set Pluralizer via Adapter
	 *
	 * @return void
	 */
	protected function setPluralizer(): void {
		$this->pluralizer = new PluralizerDoctrineInflectorAdapter( InflectorFactory::create()->build() );
	}

	/**
	 * Register bulk register post types and taxonomies
	 *
	 * @return void
	 */
	protected function register(): void {
		foreach ( self::POST_TYPE_REGISTRAR_TEST_CASES_CONFIGURATION as $test_case_subject => $test_case_bulk_registrar_arguments ) {
			$post_types = $this->bulk_registrar_post_type_factory
				->withArguments( $test_case_bulk_registrar_arguments )
				->create()
				->register();
			$this->post_types = array_merge( $this->post_types, $post_types );
		}

		foreach ( self::TAXONOMY_REGISTRAR_TEST_CASES_CONFIGURATION as $test_case_subject => $test_case_bulk_registrar_arguments ) {
			$taxonomies = $this->bulk_registrar_taxonomy_factory
				->withArguments( $test_case_bulk_registrar_arguments )
				->create()
				->register();
			$this->taxonomies = array_merge( $this->taxonomies, $taxonomies );
		}
	}

	/**
	 * Get all argument set keys
	 *
	 * @param ArgumentSetContainerInterface $argument_set_container Registration argument set container.
	 * @return array
	 */
	protected function getAllArgumentSetKeysInContainer( ArgumentSetContainerInterface $argument_set_container ): array {
		return array_map(
			fn( $argument_set ) => $argument_set->getKey(),
			[ ...$argument_set_container ]
		);
	}
}
