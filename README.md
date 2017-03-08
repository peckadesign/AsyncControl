# AsyncControl

Trait for asynchronous control rendering.

[![Downloads total](https://img.shields.io/packagist/dt/pd/async-control.svg)](https://packagist.org/packages/pd/async-control)
[![Build Status](https://travis-ci.org/peckadesign/AsyncControl.svg?branch=master)](https://travis-ci.org/peckadesign/AsyncControl)
[![Latest Stable Version](https://poser.pugx.org/pd/async-control/v/stable)](https://github.com/peckadesign/AsyncControl/releases)

Useful for loading data from external sources or any time-expensive controls.

![AsyncControl](async.gif?raw=true)

## Requirements

- [Nette/Application](https://github.com/nette/application)

## Installation

The best way to install PeckaDesign/AsyncControl is using  [Composer](http://getcomposer.org/):

```sh
$ composer require pd/async-control
```

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

### Frontend

Without any javascript this trait will show only load button. With simple script you can automatically load all asynchronous components on page load using nette ajax extension `src/assets/async.nette.ajax.js`. Alternatively you can implement your own handling, e.g. component loading while scrolling through page. This is handy if you have long pages. You do not need to wait till all data are loaded and you can show main data much faster. Additional data will be loaded depending on your JS implementation right after page load or on demand.

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
