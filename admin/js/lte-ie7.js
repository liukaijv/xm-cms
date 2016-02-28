/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'fontawesome\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-repeat' : '&#xf01e;',
			'icon-refresh' : '&#xf021;',
			'icon-ok' : '&#xf00c;',
			'icon-remove' : '&#xf00d;',
			'icon-off' : '&#xf011;',
			'icon-zoom-in' : '&#xf00e;',
			'icon-zoom-out' : '&#xf010;',
			'icon-cog' : '&#xf013;',
			'icon-trash' : '&#xf014;',
			'icon-user' : '&#xf007;',
			'icon-film' : '&#xf008;',
			'icon-th-large' : '&#xf009;',
			'icon-th' : '&#xf00a;',
			'icon-th-list' : '&#xf00b;',
			'icon-signal' : '&#xf012;',
			'icon-time' : '&#xf017;',
			'icon-road' : '&#xf018;',
			'icon-tag' : '&#xf02b;',
			'icon-tags' : '&#xf02c;',
			'icon-bookmark' : '&#xf02e;',
			'icon-print' : '&#xf02f;',
			'icon-camera' : '&#xf030;',
			'icon-font' : '&#xf031;',
			'icon-edit' : '&#xf044;',
			'icon-check' : '&#xf046;',
			'icon-move' : '&#xf047;',
			'icon-step-backward' : '&#xf048;',
			'icon-fast-backward' : '&#xf049;',
			'icon-backward' : '&#xf04a;',
			'icon-play' : '&#xf04b;',
			'icon-pause' : '&#xf04c;',
			'icon-stop' : '&#xf04d;',
			'icon-forward' : '&#xf04e;',
			'icon-fast-forward' : '&#xf050;',
			'icon-step-forward' : '&#xf051;',
			'icon-eject' : '&#xf052;',
			'icon-chevron-left' : '&#xf053;',
			'icon-chevron-right' : '&#xf054;',
			'icon-screenshot' : '&#xf05b;',
			'icon-remove-circle' : '&#xf05c;',
			'icon-ok-circle' : '&#xf05d;',
			'icon-ban-circle' : '&#xf05e;',
			'icon-arrow-left' : '&#xf060;',
			'icon-arrow-right' : '&#xf061;',
			'icon-arrow-up' : '&#xf062;',
			'icon-arrow-down' : '&#xf063;',
			'icon-share-alt' : '&#xf064;',
			'icon-resize-full' : '&#xf065;',
			'icon-resize-small' : '&#xf066;',
			'icon-plus' : '&#xf067;',
			'icon-minus' : '&#xf068;',
			'icon-asterisk' : '&#xf069;',
			'icon-exclamation-sign' : '&#xf06a;',
			'icon-gift' : '&#xf06b;',
			'icon-leaf' : '&#xf06c;',
			'icon-fire' : '&#xf06d;',
			'icon-warning-sign' : '&#xf071;',
			'icon-plane' : '&#xf072;',
			'icon-calendar' : '&#xf073;',
			'icon-random' : '&#xf074;',
			'icon-comment' : '&#xf075;',
			'icon-magnet' : '&#xf076;',
			'icon-chevron-up' : '&#xf077;',
			'icon-chevron-down' : '&#xf078;',
			'icon-retweet' : '&#xf079;',
			'icon-shopping-cart' : '&#xf07a;',
			'icon-folder-close' : '&#xf07b;',
			'icon-folder-open' : '&#xf07c;',
			'icon-resize-vertical' : '&#xf07d;',
			'icon-resize-horizontal' : '&#xf07e;',
			'icon-bar-chart' : '&#xf080;',
			'icon-twitter-sign' : '&#xf081;',
			'icon-facebook-sign' : '&#xf082;',
			'icon-camera-retro' : '&#xf083;',
			'icon-key' : '&#xf084;',
			'icon-cogs' : '&#xf085;',
			'icon-comments' : '&#xf086;',
			'icon-thumbs-up' : '&#xf087;',
			'icon-thumbs-down' : '&#xf088;',
			'icon-star-half' : '&#xf089;',
			'icon-heart-empty' : '&#xf08a;',
			'icon-signout' : '&#xf08b;',
			'icon-linkedin-sign' : '&#xf08c;',
			'icon-pushpin' : '&#xf08d;',
			'icon-external-link' : '&#xf08e;',
			'icon-trophy' : '&#xf091;',
			'icon-github-sign' : '&#xf092;',
			'icon-upload-alt' : '&#xf093;',
			'icon-lemon' : '&#xf094;',
			'icon-phone' : '&#xf095;',
			'icon-check-empty' : '&#xf096;',
			'icon-bookmark-empty' : '&#xf097;',
			'icon-phone-sign' : '&#xf098;',
			'icon-twitter' : '&#xf099;',
			'icon-facebook' : '&#xf09a;',
			'icon-github' : '&#xf09b;',
			'icon-unlock' : '&#xf09c;',
			'icon-credit' : '&#xf09d;',
			'icon-rss' : '&#xf09e;',
			'icon-hdd' : '&#xf0a0;',
			'icon-bullhorn' : '&#xf0a1;',
			'icon-bell' : '&#xf0a2;',
			'icon-certificate' : '&#xf0a3;',
			'icon-hand-right' : '&#xf0a4;',
			'icon-hand-left' : '&#xf0a5;',
			'icon-hand-up' : '&#xf0a6;',
			'icon-hand-down' : '&#xf0a7;',
			'icon-circle-arrow-left' : '&#xf0a8;',
			'icon-circle-arrow-right' : '&#xf0a9;',
			'icon-circle-arrow-up' : '&#xf0aa;',
			'icon-circle-arrow-down' : '&#xf0ab;',
			'icon-globe' : '&#xf0ac;',
			'icon-wrench' : '&#xf0ad;',
			'icon-tasks' : '&#xf0ae;',
			'icon-filter' : '&#xf0b0;',
			'icon-briefcase' : '&#xf0b1;',
			'icon-fullscreen' : '&#xf0b2;',
			'icon-group' : '&#xf0c0;',
			'icon-link' : '&#xf0c1;',
			'icon-cloud' : '&#xf0c2;',
			'icon-beaker' : '&#xf0c3;',
			'icon-cut' : '&#xf0c4;',
			'icon-copy' : '&#xf0c5;',
			'icon-paper-clip' : '&#xf0c6;',
			'icon-save' : '&#xf0c7;',
			'icon-sign-blank' : '&#xf0c8;',
			'icon-reorder' : '&#xf0c9;',
			'icon-list-ul' : '&#xf0ca;',
			'icon-list-ol' : '&#xf0cb;',
			'icon-strikethrough' : '&#xf0cc;',
			'icon-underline' : '&#xf0cd;',
			'icon-table' : '&#xf0ce;',
			'icon-magic' : '&#xf0d0;',
			'icon-truck' : '&#xf0d1;',
			'icon-pinterest' : '&#xf0d2;',
			'icon-pinterest-sign' : '&#xf0d3;',
			'icon-google-plus-sign' : '&#xf0d4;',
			'icon-google-plus' : '&#xf0d5;',
			'icon-money' : '&#xf0d6;',
			'icon-caret-down' : '&#xf0d7;',
			'icon-caret-up' : '&#xf0d8;',
			'icon-caret-left' : '&#xf0d9;',
			'icon-caret-right' : '&#xf0da;',
			'icon-columns' : '&#xf0db;',
			'icon-sort' : '&#xf0dc;',
			'icon-sort-down' : '&#xf0dd;',
			'icon-sort-up' : '&#xf0de;',
			'icon-envelope-alt' : '&#xf0e0;',
			'icon-linkedin' : '&#xf0e1;',
			'icon-undo' : '&#xf0e2;',
			'icon-legal' : '&#xf0e3;',
			'icon-dashboard' : '&#xf0e4;',
			'icon-comments-alt' : '&#xf0e6;',
			'icon-bolt' : '&#xf0e7;',
			'icon-sitemap' : '&#xf0e8;',
			'icon-umbrella' : '&#xf0e9;',
			'icon-paste' : '&#xf0ea;',
			'icon-lightbulb' : '&#xf0eb;',
			'icon-exchange' : '&#xf0ec;',
			'icon-cloud-download' : '&#xf0ed;',
			'icon-cloud-upload' : '&#xf0ee;',
			'icon-stethoscope' : '&#xf0f1;',
			'icon-suitcase' : '&#xf0f2;',
			'icon-bell-alt' : '&#xf0f3;',
			'icon-coffee' : '&#xf0f4;',
			'icon-food' : '&#xf0f5;',
			'icon-file-alt' : '&#xf0f6;',
			'icon-building' : '&#xf0f7;',
			'icon-ambulance' : '&#xf0f9;',
			'icon-medkit' : '&#xf0fa;',
			'icon-fighter-jet' : '&#xf0fb;',
			'icon-beer' : '&#xf0fc;',
			'icon-h-sign' : '&#xf0fd;',
			'icon-plus-sign' : '&#xf0fe;',
			'icon-double-angle-left' : '&#xf100;',
			'icon-double-angle-right' : '&#xf101;',
			'icon-double-angle-up' : '&#xf102;',
			'icon-double-angle-down' : '&#xf103;',
			'icon-laptop' : '&#xf109;',
			'icon-tablet' : '&#xf10a;',
			'icon-circle-blank' : '&#xf10c;',
			'icon-quote-left' : '&#xf10d;',
			'icon-quote-right' : '&#xf10e;',
			'icon-spinner' : '&#xf110;',
			'icon-circle' : '&#xf111;',
			'icon-github-alt' : '&#xf113;',
			'icon-folder-close-alt' : '&#xf114;',
			'icon-folder-open-alt' : '&#xf115;',
			'icon-list-alt' : '&#xf022;',
			'icon-lock' : '&#xf023;',
			'icon-flag' : '&#xf024;',
			'icon-headphones' : '&#xf025;',
			'icon-volume-off' : '&#xf026;',
			'icon-volume-down' : '&#xf027;',
			'icon-volume-up' : '&#xf028;',
			'icon-qrcode' : '&#xf029;',
			'icon-barcode' : '&#xf02a;',
			'icon-align-left' : '&#xf036;',
			'icon-align-center' : '&#xf037;',
			'icon-align-right' : '&#xf038;',
			'icon-align-justify' : '&#xf039;',
			'icon-list' : '&#xf03a;',
			'icon-indent-left' : '&#xf03b;',
			'icon-indent-right' : '&#xf03c;',
			'icon-facetime-video' : '&#xf03d;',
			'icon-picture' : '&#xf03e;',
			'icon-text-width' : '&#xf035;',
			'icon-text-height' : '&#xf034;',
			'icon-italic' : '&#xf033;',
			'icon-bold' : '&#xf032;',
			'icon-play-circle' : '&#xf01d;',
			'icon-inbox' : '&#xf01c;',
			'icon-upload' : '&#xf01b;',
			'icon-download' : '&#xf01a;',
			'icon-star-empty' : '&#xf006;',
			'icon-star' : '&#xf005;',
			'icon-heart' : '&#xf004;',
			'icon-envelope' : '&#xf003;',
			'icon-search' : '&#xf002;',
			'icon-glass' : '&#xf000;',
			'icon-home' : '&#xf015;',
			'icon-file' : '&#xf016;',
			'icon-pencil' : '&#xf040;',
			'icon-map-marker' : '&#xf041;',
			'icon-adjust' : '&#xf042;',
			'icon-tint' : '&#xf043;',
			'icon-plus-sign-2' : '&#xf055;',
			'icon-minus-sign' : '&#xf056;',
			'icon-remove-sign' : '&#xf057;',
			'icon-ok-sign' : '&#xf058;',
			'icon-question-sign' : '&#xf059;',
			'icon-info-sign' : '&#xf05a;',
			'icon-share' : '&#xf045;',
			'icon-eye-close' : '&#xf070;',
			'icon-eye-open' : '&#xf06e;',
			'icon-user-md' : '&#xf0f0;',
			'icon-signin' : '&#xf090;',
			'icon-angle-left' : '&#xf104;',
			'icon-angle-right' : '&#xf105;',
			'icon-angle-up' : '&#xf106;',
			'icon-angle-down' : '&#xf107;',
			'icon-mobile' : '&#xf10b;',
			'icon-reply' : '&#xf112;',
			'icon-hospital' : '&#xf0f8;',
			'icon-comment-alt' : '&#xf0e5;',
			'icon-download-alt' : '&#xf019;',
			'icon-book' : '&#xf02d;',
			'icon-music' : '&#xf001;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
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