<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\ArgumentSetContainers;

use martinsluters\WPRegistrars\{ ArgumentSetInterface, ArgumentSetContainerInterface };
use ArrayIterator;

/**
 * Abstract collection (container) class of ArgumentSet
 */
abstract class AbstractArgumentSetContainer implements ArgumentSetContainerInterface {

	/**
	 *
	 * ArgumentSets
	 *
	 * @var array
	 */
	protected array $argument_sets;

	/**
	 * Class constructor
	 *
	 * @param ArgumentSetInterface ...$argument_sets Variable-length argument lists of ArgumentSet(s).
	 */
	public function __construct( ArgumentSetInterface ...$argument_sets ) {
		$this->argument_sets = $argument_sets;
	}

	/**
	 * Add argument set to container
	 *
	 * @param ArgumentSetInterface $argument_set ArgumentSet.
	 * @return void
	 */
	public function addArgumentSet( ArgumentSetInterface $argument_set ) {
		$this->argument_sets[] = $argument_set;
	}

	/**
	 * Validity of container content
	 *
	 * @return boolean
	 */
	public function isValidContainerContent(): bool {
		return 0 < $this->count();
	}

	/**
	 * Implementation of getIterator IteratorAggregate interface method
	 *
	 * @return ArrayIterator
	 */
	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->argument_sets );
	}

	/**
	 * Implementation of count Countable interface method
	 *
	 * @return int
	 */
	public function count(): int {
		return \count( $this->argument_sets );
	}
}
