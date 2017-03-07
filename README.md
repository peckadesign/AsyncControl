# AsyncControl

Trait for asynchronous control rendering.

## Usage

### Trait

```php
<?php
class CommentsControl extends Nette\Application\UI\Control {

	use Pd\AsyncControl\UI\AsyncControlTrait;

	public function render() {
		//control rendering
	}
}
```

If you want to call different method than `render` set custom render callback:

```php
<?php
$this->setAsyncRenderer([$this, 'customRender']);
//or
$this->setAsyncRenderer(function () {
	//control rendering
});
```

### Template

```latte
{control comments:async}
```

or with custom message

```latte
{control comments:async 'Show comments'}
```

## Configuring

You can set default message and attributes used for loading link in `bootstrap.php`

```php
<?php
Pd\AsyncControl\UI\AsyncControlLink::setDefault('Load content', ['class' => ['btn', 'ajax']]);
```

or in application setup

```neon
services:
	application:
		setup:
			- Pd\AsyncControl\UI\AsyncControlLink::setDefault('Load content', {class: [btn, ajax]})
```

## Search engines

To allow indexing of your site by crawlers you need to add meta tag to your page.

```latte
<meta name="fragment" content="!" n:if="$presenter->getParameter('_escaped_fragment_') === NULL">
```

> If you place into the page www.example.com, the crawler will temporarily map this URL to www.example.com?_escaped_fragment_= and will request this from your server. Your server should then return the HTML snapshot corresponding to www.example.com

When parameter `_escaped_fragment_` is present in url `AsyncControlTrait` will always render its content.

If you want the same behaviour for visitors of your page with disabled JS, add additional meta tag within noscript tag:

```latte
<noscript n:if="$presenter->getParameter('_escaped_fragment_') === NULL">
	<meta http-equiv="refresh" content="0;url=?_escaped_fragment_="/>
</noscript>
```

To avoid extra load on your servers and crawlers use these tags only on pages containing controls with trait `AsyncControlTrait`.
