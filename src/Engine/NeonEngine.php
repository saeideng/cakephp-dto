<?php
namespace CakeDto\Engine;

use InvalidArgumentException;
use Nette\Neon\Exception;
use Nette\Neon\Neon;

class NeonEngine implements EngineInterface {

	const EXT = 'neon';

	/**
	 * @return string
	 */
	public function extension() {
		return static::EXT;
	}

	/**
	 * @param array $files
	 * @return void
	 */
	public function validate(array $files) {
	}

	/**
	 * Parses content into array form. Can also already contain basic validation
	 * if validate() cannot be used.
	 *
	 * @param string $content
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function parse($content) {
		$result = [];

		try {
			$result = Neon::decode($content);
		} catch (Exception $e) {
			throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
		}

		if ($result === null) {
			throw new InvalidArgumentException(sprintf('%s: invalid neon content.', __CLASS__));
		}

		foreach ($result as $name => $dto) {
			$dto['name'] = $name;

			$fields = isset($dto['fields']) ? $dto['fields'] : [];
			foreach ($fields as $key => $field) {
				if (is_string($field)) {
					$field = [
						'type' => $field,
					];
				}
				$field['name'] = $key;
				$fields[$key] = $field;
			}

			$dto['fields'] = $fields;

			$result[$name] = $dto;
		}

		return $result;
	}

}
