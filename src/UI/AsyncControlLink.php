<?php declare(strict_types = 1);

namespace Pd\AsyncControl\UI;

final class AsyncControlLink
{

	private static $defaultMessage = 'Load content';

	private static $defaultAttributes = [];

	private string $message;

	private array $attributes;

	public function __construct(
		?string $message = null,
		?array $attributes = null
	)
	{
		$this->message = $message === null ? self::$defaultMessage : $message;
		$this->attributes = $attributes === null ? self::$defaultAttributes : $attributes;
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
