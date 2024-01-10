<?php declare(strict_types = 1);

namespace Pd\AsyncControl\UI;

use Mockery;

use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Application\UI\Presenter;
use Tester\Assert;
use Tester\TestCase;


require_once __DIR__ . '/../../vendor/autoload.php';

\Tester\Environment::bypassFinals();

/**
 * @testCase
 */
final class AsyncControlTest extends TestCase
{


	public function testHandleAjax(): void
	{
		$presenter = Mockery::mock(Presenter::class);
		$presenter->shouldReceive('isAjax')->once()->andReturn(TRUE);
		$presenter->shouldReceive('getPayload')->andReturn($payload = new \stdClass());
		$presenter->shouldReceive('sendPayload')->once();

		$control = Mockery::mock(AsyncControl::class)->makePartial()->shouldAllowMockingProtectedMethods();
		$control->shouldReceive('getPresenter')->andReturn($presenter);
		$renderedContent = 'rendered content';
		$control->shouldReceive('doRender')->once()->andReturnUsing(function () use ($renderedContent) {
			echo $renderedContent;
		})
		;
		$control->shouldReceive('getSnippetId')->with('async')->andReturn($snippetId = 'snippet-control-async');
		$control->handleAsyncLoad();

		Assert::equal(['snippets' => [$snippetId => $renderedContent]], (array) $payload);
	}


	public function testHandleNoAjax(): void
	{
		$presenter = Mockery::mock(Presenter::class);
		$presenter->shouldReceive('isAjax')->once()->andReturn(FALSE);
		$presenter->shouldNotReceive('getPayload');
		$presenter->shouldNotReceive('sendPayload');

		$control = Mockery::mock(AsyncControl::class)->makePartial();
		$control->shouldReceive('getPresenter')->andReturn($presenter);
		$control->shouldNotReceive('renderAsync');
		$control->shouldNotReceive('getSnippetId');
		$control->handleAsyncLoad();
	}


	public function testRenderAsyncLoadsLink(): void
	{
		$control = Mockery::mock(AsyncControl::class)->makePartial();

		$template = Mockery::mock(Template::class);
		$template->shouldReceive('add')->once()->with('link', Mockery::type(AsyncControlLink::class));
		$template->shouldReceive('setFile')->once()->withAnyArgs();
		$template->shouldReceive('render')->once();

		$templateFactory = Mockery::mock(TemplateFactory::class);
		$templateFactory->shouldReceive('createTemplate')->once()->with($control)->andReturn($template);

		$presenter = Mockery::mock(Presenter::class);
		$presenter->shouldReceive('getTemplateFactory')->once()->andReturn($templateFactory);

		$control->shouldReceive('getPresenter')->once()->andReturn($presenter);
		$control->renderAsync();
	}


	public function testRenderAsyncRenderer(): void
	{
		$control = Mockery::mock(AsyncControl::class)->makePartial()->shouldAllowMockingProtectedMethods();
		$asyncRendered = FALSE;
		$control->setAsyncRenderer(function () use (&$asyncRendered) {
			$asyncRendered = TRUE;
		});
		$control->doRender();
		Assert::equal(TRUE, $asyncRendered);
	}


	protected function tearDown(): void
	{
		parent::tearDown();
		Mockery::close();
	}
}


(new AsyncControlTest)->run();
