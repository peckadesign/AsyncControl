(function ($) {
	$.nette.ext({
		init: function () {
			this.init();
		},
		success: function () {
			this.init();
		}
	}, {
		init: function () {
			$('[data-async]').each(function () {
				var $this = $(this);
				if ($this.data('asyncInitialized')) {
					return;
				}
				$this.data('asyncInitialized', true);
				$.nette.ajax({
					url: $this.data('asyncLink') || $this.attr('href'),
					off: ['history', 'unique']
				}, $this, new CustomEvent('asyncLoad'));
			});
		}
	});
})(window.jQuery);
