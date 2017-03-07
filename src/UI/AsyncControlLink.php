<?php declare(strict_types = 1);

namespace Pd\AsyncControl\UI;

final class AsyncControlLink
{

	private static $defaultMessage = 'Load content';
	private static $defaultAttributes = [];
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var array
	 */
	private $attributes;


	public function __construct(
		string $message = NULL,
		array $attributes = NULL
	) {
		$this->message = $message === NULL ? self::$defaultMessage : $message;
		$this->attributes = $attributes === NULL ? self::$defaultAttributes : $attributes;
	}


	public static function setDefault(string $message, array $attributes = [])
	{
		self::$defaultMessage = $message;
		self::$defaultAttributes = $attributes;
	}


	public function getMessage(): string
	{
		return $this->message;
	}


	public function getAttributes(): array
	{
		return $this->attributes;
	}
}
