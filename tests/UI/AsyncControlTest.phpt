<?php declare(strict_types = 1);

namespace Pd\AsyncControl\UI;

use Mockery;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\ITemplateFactory;
use Nette\Application\UI\Presenter;
use Tester\Assert;
use Tester\TestCase;


require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * @testCase
 */
final class AsyncControlTest extends TestCase
{

	const VALID_SIGNAL = 'control-form-submit';
	const FRAGMENT_PARAMETER = '_escaped_fragment_';


	public function testHandleAjax(): void
	{
		$presenter = Mockery::mock(Presenter::class);
		$presenter->shouldReceive('isAjax')->once()->andReturn(TRUE);
		$presenter->shouldReceive('getPayload')->andReturn($payload = new \stdClass);
		$presenter->shouldReceive('sendPayload')->once();
		/**
		 * @var AsyncControl|Mockery\Mock $control
		 */
		$control = Mockery::mock(AsyncControl::class)->makePartial();
		$control->shouldReceive('getPresenter')->andReturn($presenter);
		$renderedContent = 'rendered content';
		$control->shouldReceive('renderAsync')->once()->andReturnUsing(function () use ($renderedContent) {
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
		/**
		 * @var AsyncControl|Mockery\Mock $control
		 */
		$control = Mockery::mock(AsyncControl::class)->makePartial();
		$control->shouldReceive('getPresenter')->andReturn($presenter);
		$control->shouldNotReceive('renderAsync');
		$control->shouldNotReceive('getSnippetId');
		$control->handleAsyncLoad();
	}


	public function testRenderAsyncLoadLink(): void
	{
		/**
		 * @var AsyncControl|Mockery\Mock $control
		 */
		$control = Mockery::mock(AsyncControl::class)->makePartial();

		$template = Mockery::mock(ITemplate::class);
		$template->shouldReceive('setFile')->once()->withAnyArgs();
		$template->shouldReceive('render')->once();

		$templateFactory = Mockery::mock(ITemplateFactory::class);
		$templateFactory->shouldReceive('createTemplate')->once()->with($control)->andReturn($template);

		$presenter = Mockery::mock(Presenter::class);
		$presenter->shouldReceive('getParameter')->once()->with(self::FRAGMENT_PARAMETER)->andReturn(NULL);
		$presenter->shouldReceive('getParameter')->once()->with(Presenter::SIGNAL_KEY)->andReturn(NULL);
		$presenter->shouldReceive('getTemplateFactory')->once()->andReturn($templateFactory);

		$control->shouldReceive('getPresenter')->andReturn($presenter);
		$control->shouldReceive('getUniqueId')->once()->andReturn('control');
		$control->renderAsync();
	}


	public function testRenderWithSignal(): void
	{
		$presenter = Mockery::mock(Presenter::class);
		$presenter->shouldReceive('getParameter')->once()->with(self::FRAGMENT_PARAMETER)->andReturn(NULL);
		$presenter->shouldReceive('getParameter')->once()->with(Presenter::SIGNAL_KEY)->andReturn(self::VALID_SIGNAL);
		/**
		 * @var AsyncControl|Mockery\Mock $control
		 */
		$control = Mockery::mock(AsyncControl::class)->makePartial();
		$control->shouldReceive('getPresenter')->andReturn($presenter);
		$control->shouldReceive('getUniqueId')->once()->andReturn('control');
		$control->shouldReceive('render')->once();
		$control->renderAsync();
	}


	public function testRenderWithFragment(): void
	{
		$presenter = Mockery::mock(Presenter::class);
		$presenter->shouldReceive('getParameter')->once()->with(self::FRAGMENT_PARAMETER)->andReturn('');
		/**
		 * @var AsyncControl|Mockery\Mock $control
		 */
		$control = Mockery::mock(AsyncControl::class)->makePartial();
		$control->shouldReceive('getPresenter')->andReturn($presenter);
		$control->shouldReceive('render')->once();
		$control->renderAsync();
	}


	public function testRenderAsyncRenderer(): void
	{
		$presenter = Mockery::mock(Presenter::class);
		$presenter->shouldReceive('getParameter')->once()->with(self::FRAGMENT_PARAMETER)->andReturn(NULL);
		$presenter->shouldReceive('getParameter')->once()->with(Presenter::SIGNAL_KEY)->andReturn(self::VALID_SIGNAL);
		/**
		 * @var AsyncControl|Mockery\Mock $control
		 */
		$control = Mockery::mock(AsyncControl::class)->makePartial();
		$control->shouldReceive('getPresenter')->andReturn($presenter);
		$control->shouldReceive('getUniqueId')->once()->andReturn('control');
		$asyncRendered = FALSE;
		$control->setAsyncRenderer(function () use (&$asyncRendered) {
			$asyncRendered = TRUE;
		});
		$control->renderAsync();
		Assert::equal(TRUE, $asyncRendered);
	}


	protected function tearDown(): void
	{
		parent::tearDown();
		Mockery::close();
	}
}


(new AsyncControlTest)->run();
