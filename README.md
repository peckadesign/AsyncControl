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
