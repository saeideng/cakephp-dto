<?php

namespace CakeDto\Dto;

use CakeDto\View\Json;

abstract class AbstractImmutableDto extends Dto {

	/**
	 * @param array $data
	 * @param bool $ignoreMissing
	 * @param string $type
	 * @return static
	 */
	public static function createFromArray(array $data, $ignoreMissing = false, $type = self::TYPE_DEFAULT) {
		return new static($data, $ignoreMissing, $type);
	}

	/**
	 * @param string $data
	 * @param bool $ignoreMissing
	 * @return static
	 */
	public static function fromUnserialized($data, $ignoreMissing = false) {
		$jsonUtil = new Json();

		return new static($jsonUtil->decode($data, true), $ignoreMissing, static::TYPE_DEFAULT);
	}

	/**
	 * Constructs the object
	 *
	 * @link https://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized
	 * @param bool $ignoreMissing
	 * @return static
	 */
	public function unserialize($serialized, $ignoreMissing = false) {
		$jsonUtil = new Json();

		$new = clone($this);
		$new->setFromArray($jsonUtil->decode($serialized, true), $ignoreMissing, static::TYPE_DEFAULT)->setDefaults()->validate();

		return $new;
	}

}
