<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\ArgumentSetContainers;

use martinsluters\WPRegistrars\ArgumentSetContainers\AbstractArgumentSetContainer;
use martinsluters\WPRegistrars\ArgumentSetInterface;
use martinsluters\WPRegistrars\Tests\Unit\TestCase;
use Mockery;

/**
 * Tests of Abstract Argument Set Container.
 */
class AbstractArgumentSetContainerTest extends TestCase {

	/**
	 * Argument set dummy 1
	 *
	 * @var ArgumentSetInterface
	 */
	private ArgumentSetInterface $argument_set_1_dummy;

	/**
	 * Argument set dummy 2
	 *
	 * @var ArgumentSetInterface
	 */
	private ArgumentSetInterface $argument_set_2_dummy;

	/**
	 * Argument set container instance
	 *
	 * @var AbstractArgumentSetContainer
	 */
	private AbstractArgumentSetContainer $argument_set_container_instance;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->argument_set_container_instance = $this->getNewAnonymousAbstractArgumentSetContainer();
		$this->argument_set_2_dummy = Mockery::mock( ArgumentSetInterface::class );
		$this->argument_set_1_dummy = Mockery::mock( ArgumentSetInterface::class );
	}

	/**
	 * Provides a new abstract argument set container
	 *
	 * @param ArgumentSetInterface ...$argument_sets Variable-length list of argument sets.
	 * @return AbstractArgumentSetContainer
	 */
	private function getNewAnonymousAbstractArgumentSetContainer( ArgumentSetInterface ...$argument_sets ): AbstractArgumentSetContainer {
		return new class( ...$argument_sets ) extends AbstractArgumentSetContainer{};
	}

	/**
	 * Container must implement SPL ArrayIterator.
	 *
	 * @return void
	 */
	public function testArgumentSetContainerImplementsIteratorAggregate(): void {
		$this->assertInstanceOf( '\IteratorAggregate', $this->argument_set_container_instance );
	}

	/**
	 * Container must implement SPL Countable.
	 *
	 * @return void
	 */
	public function testArgumentSetContainerImplementsCountable(): void {
		$this->assertInstanceOf( '\Countable', $this->argument_set_container_instance );
	}

	/**
	 * Container content is valid if not empty
	 *
	 * @return void
	 */
	public function testContainerContentValidity(): void {

		$argument_set_container_instance = $this->getNewAnonymousAbstractArgumentSetContainer();
		$this->assertFalse( $argument_set_container_instance->isValidContainerContent() );

		$argument_set_container_instance = $this->getNewAnonymousAbstractArgumentSetContainer( $this->argument_set_1_dummy );
		$this->assertTrue( $argument_set_container_instance->isValidContainerContent() );

		$argument_set_container_instance = $this->getNewAnonymousAbstractArgumentSetContainer( ...[ $this->argument_set_1_dummy, $this->argument_set_2_dummy ] );
		$this->assertTrue( $argument_set_container_instance->isValidContainerContent() );

		$argument_set_container_instance = $this->getNewAnonymousAbstractArgumentSetContainer( $this->argument_set_1_dummy, $this->argument_set_2_dummy );
		$this->assertTrue( $argument_set_container_instance->isValidContainerContent() );

		$argument_set_container_instance = $this->getNewAnonymousAbstractArgumentSetContainer();
		$argument_set_container_instance->addArgumentSet( $this->argument_set_1_dummy );
		$this->assertTrue( $argument_set_container_instance->isValidContainerContent() );
	}

	/**
	 * Make sure that the count of argument sets in container match expected number of argument sets
	 *
	 * @return void
	 */
	public function testCountContainerShouldReturnExpectedValue(): void {
		$argument_set_container_instance = $this->getNewAnonymousAbstractArgumentSetContainer();
		$this->assertSame( 0, \count( $argument_set_container_instance ) );

		$argument_set_container_instance = $this->getNewAnonymousAbstractArgumentSetContainer( $this->argument_set_1_dummy );
		$this->assertSame( 1, \count( $argument_set_container_instance ) );

		$argument_set_container_instance = $this->getNewAnonymousAbstractArgumentSetContainer( $this->argument_set_1_dummy, $this->argument_set_2_dummy );
		$this->assertSame( 2, \count( $argument_set_container_instance ) );
	}
}
