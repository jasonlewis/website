$(document).ready(function()
{
	var binder = {
		isBound: false,
		initialize: function()
		{
			if ($(window).width() < 801)
			{
				binder.unbind();
			}
			else
			{
				binder.bind();
			}
		},
		bind: function()
		{
			if (this.isBound) return;

			this.isBound = true;

			$('.tooltip-ui').tooltip({ position: 'bottom', offset: 4 });
		},
		unbind: function()
		{
			this.isBound = false;

			$('.tooltip-ui').off('mouseover').off('mouseout');
		}
	}

	$(window).resize(binder.initialize);

	binder.initialize();

	$('div.docs-toc').on('click', function()
	{
		$(this).next('ul').toggle();
	});
});