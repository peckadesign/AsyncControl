<?php declare(strict_types = 1);

namespace Pd\AsyncControl\UI;

final class AsyncControlLink
{

	private static string $defaultMessage = 'Load content';

	/**
	 * @var array<string, string>
	 */
	private static array $defaultAttributes = [];

	private string $message;

	/**
	 * @var array<string, string>
	 */
	private array $attributes;

	/**
	 * @param string|null $message
	 * @param array<string, string> $attributes
	 */
	public function __construct(
		?string $message = null,
		?array $attributes = null
	)
	{
		$this->message = $message === null ? self::$defaultMessage : $message;
		$this->attributes = $attributes === null ? self::$defaultAttributes : $attributes;
	}

	/**
	 * @param array<string, string>  $attributes
	 */
	public static function setDefault(string $message, array $attributes = []): void
	{
		self::$defaultMessage = $message;
		self::$defaultAttributes = $attributes;
	}

	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * @return array<string, string>
	 */
	public function getAttributes(): array
	{
		return $this->attributes;
	}

}
