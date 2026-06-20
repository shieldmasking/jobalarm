var tj = tj || {};

jQuery(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo(theme settings page)
    Index.init(); // init index page
    Tasks.initDashboardWidget(); // init tash dashboard widget
	TableAjax.init();
});