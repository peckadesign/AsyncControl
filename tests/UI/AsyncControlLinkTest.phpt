<?php declare(strict_types = 1);

namespace Pd\AsyncControl\UI;

use Tester\Assert;
use Tester\TestCase;


require_once __DIR__ . '/../../vendor/autoload.php';


final class AsyncControlLinkTest extends TestCase
{

	public function testLink()
	{
		$link = new AsyncControlLink;
		Assert::equal('Load content', $link->getMessage());
		Assert::equal([], $link->getAttributes());

		$link = new AsyncControlLink('Custom message', ['foo' => 'bar']);
		Assert::equal('Custom message', $link->getMessage());
		Assert::equal(['foo' => 'bar'], $link->getAttributes());

		AsyncControlLink::setDefault('Default message', ['bar' => 'baz']);

		$link = new AsyncControlLink;
		Assert::equal('Default message', $link->getMessage());
		Assert::equal(['bar' => 'baz'], $link->getAttributes());

		$link = new AsyncControlLink('Custom message', ['foo' => 'bar']);
		Assert::equal('Custom message', $link->getMessage());
		Assert::equal(['foo' => 'bar'], $link->getAttributes());
	}
}


(new AsyncControlLinkTest)->run();
