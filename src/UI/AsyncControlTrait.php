<?php declare(strict_types = 1);

namespace Pd\AsyncControl\UI;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\PresenterComponent;


/**
 * @method render
 */
trait AsyncControlTrait
{

	/**
	 * @var callable
	 */
	protected $asyncRenderer;


	public function handleAsyncLoad()
	{
		if ( ! $this instanceof PresenterComponent || ! ($presenter = $this->getPresenter(FALSE)) || ! $presenter->isAjax()) {
			return;
		}
		ob_start(function () {
		});
		try {
			$this->renderAsync();
		} catch (\Throwable $e) {
			ob_end_clean();
			throw $e;
		} catch (\Exception $e) {
			ob_end_clean();
			throw $e;
		}
		$content = ob_get_clean();
		$presenter->getPayload()->snippets[$this->getSnippetId('async')] = $content;
		$presenter->sendPayload();
	}


	public function renderAsync(string $linkMessage = NULL, array $linkAttributes = NULL)
	{
		if ($this instanceof Control && strpos((string) $this->getPresenter()->getParameter(Presenter::SIGNAL_KEY), sprintf('%s-', $this->getUniqueId())) !== 0) {
			$template = $this->createTemplate();
			$template->link = new AsyncControlLink($linkMessage, $linkAttributes);
			$template->setFile(__DIR__ . '/templates/asyncLoadLink.latte');
			$template->render();
		} elseif (is_callable($this->asyncRenderer)) {
			call_user_func($this->asyncRenderer);
		} else {
			$this->render();
		}
	}


	public function setAsyncRenderer(callable $renderer)
	{
		$this->asyncRenderer = $renderer;
	}
}
