/* Use this script if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-view-list' : '&#xe000;',
			'icon-view-map' : '&#xe001;',
			'icon-view-object' : '&#xe002;',
			'icon-view-time' : '&#xe003;',
			'icon-arrow-thin-down' : '&#xe004;',
			'icon-arrow-right' : '&#xe005;',
			'icon-arrow-left' : '&#xe006;',
			'icon-arrow-down' : '&#xe007;',
			'icon-twitter' : '&#xe008;',
			'icon-rss' : '&#xe009;',
			'icon-mag-glass' : '&#xe00a;',
			'icon-lock' : '&#xe00b;',
			'icon-facebook' : '&#xe00c;',
			'icon-arrow-up' : '&#xe00d;',
			'icon-arrow-thin-up' : '&#xe00e;',
			'icon-arrow-thin-right' : '&#xe00f;',
			'icon-arrow-thin-left' : '&#xe010;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; i < els.length; i += 1) {
		el = els[i];
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};