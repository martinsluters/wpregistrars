<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars;

trait CapitalizableTrait {

	/**
	 * Flag indicating if should auto capitalize
	 *
	 * @var bool
	 */
	protected bool $do_auto_capitalize = true;

	/**
	 * Setter method of do_auto_capitalize
	 *
	 * @param bool $should_auto_capitalize New value of do_auto_capitalize.
	 * @return void
	 */
	public function setDoAutoCapitalize( bool $should_auto_capitalize ): void {
		$this->do_auto_capitalize = $should_auto_capitalize;
	}

	/**
	 * Getter method of do_auto_capitalize
	 *
	 * @return bool Current value of do_auto_capitalize.
	 */
	public function getDoAutoCapitalize(): bool {
		return $this->do_auto_capitalize;
	}
}
