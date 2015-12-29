<?php

/* FONTS CLASS (originally used in Thesis theme) */
/*------------------------------------------------------------------*/

class Fonts {
	function set_fonts() {
		$this->fonts = array(
			'arial' => array(
				'name' => 'Arial',
				'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif',
				'web_safe' => true,
				'monospace' => false,
				'google' => false
			),
			'arial_black' => array(
				'name' => 'Arial Black',
				'family' => '"Arial Black", "Arial Bold", Arial, sans-serif',
				'web_safe' => true,
				'monospace' => false,
				'google' => false
			),
			'arial_narrow' => array(
				'name' => 'Arial Narrow',
				'family' => '"Arial Narrow", Arial, "Helvetica Neue", Helvetica, sans-serif',
				'web_safe' => true,
				'monospace' => false,
				'google' => false
			),
			'courier_new' => array(
				'name' => 'Courier New',
				'family' => '"Courier New", Courier, Verdana, sans-serif',
				'web_safe' => true,
				'monospace' => false,
				'google' => false
			),
			'georgia' => array(
				'name' => 'Georgia',
				'family' => 'Georgia, "Times New Roman", Times, serif',
				'web_safe' => true,
				'monospace' => false,
				'google' => false
			),
			'tahoma' => array(
				'name' => 'Tahoma',
				'family' => 'Tahoma, Geneva, Verdana, sans-serif',
				'web_safe' => true,
				'monospace' => false,
				'google' => false
			),
			'times_new_roman' => array(
				'name' => 'Times New Roman',
				'family' => '"Times New Roman", Times, Georgia, serif',
				'web_safe' => true,
				'monospace' => false,
				'google' => false
			),
			'trebuchet_ms' => array(
				'name' => 'Trebuchet MS',
				'family' => '"Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Arial, sans-serif',
				'web_safe' => true,
				'monospace' => false,
				'google' => false
			),
			'verdana' => array(
				'name' => 'Verdana',
				'family' => 'Verdana, sans-serif',
				'web_safe' => true,
				'monospace' => false,
				'google' => false
			),
			'andale' => array(
				'name' => 'Andale Mono',
				'family' => '"Andale Mono", Consolas, Monaco, Courier, "Courier New", Verdana, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'baskerville' => array(
				'name' => 'Baskerville',
				'family' => 'Baskerville, "Times New Roman", Times, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'bookman_old_style' => array(
				'name' => 'Bookman Old Style',
				'family' => '"Bookman Old Style", Georgia, "Times New Roman", Times, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'calibri' => array(
				'name' => 'Calibri',
				'family' => 'Calibri, "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'cambria' => array(
				'name' => 'Cambria',
				'family' => 'Cambria, Georgia, "Times New Roman", Times, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'candara' => array(
				'name' => 'Candara',
				'family' => 'Candara, Verdana, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'century_gothic' => array(
				'name' => 'Century Gothic',
				'family' => '"Century Gothic", "Apple Gothic", Verdana, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'century_schoolbook' => array(
				'name' => 'Century Schoolbook',
				'family' => '"Century Schoolbook", Georgia, "Times New Roman", Times, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'consolas' => array(
				'name' => 'Consolas',
				'family' => 'Consolas, "Andale Mono", Monaco, Courier, "Courier New", Verdana, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'constantia' => array(
				'name' => 'Constantia',
				'family' => 'Constantia, Georgia, "Times New Roman", Times, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'corbel' => array(
				'name' => 'Corbel',
				'family' => 'Corbel, "Lucida Grande", "Lucida Sans Unicode", Arial, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'franklin_gothic' => array(
				'name' => 'Franklin Gothic Medium',
				'family' => '"Franklin Gothic Medium", Arial, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'garamond' => array(
				'name' => 'Garamond',
				'family' => 'Garamond, "Hoefler Text", "Times New Roman", Times, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'gill_sans' => array(
				'name' => 'Gill Sans',
				'family' => '"Gill Sans MT", "Gill Sans", Calibri, "Trebuchet MS", sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'helvetica' => array(
				'name' => 'Helvetica',
				'family' => '"Helvetica Neue", Helvetica, Arial, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'hoefler' => array(
				'name' => 'Hoefler Text',
				'family' => '"Hoefler Text", Garamond, "Times New Roman", Times, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'lucida_bright' => array(
				'name' => 'Lucida Bright',
				'family' => '"Lucida Bright", Cambria, Georgia, "Times New Roman", Times, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'lucida_grande' => array(
				'name' => 'Lucida Grande',
				'family' => '"Lucida Grande", "Lucida Sans", "Lucida Sans Unicode", sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'palatino' => array(
				'name' => 'Palatino',
				'family' => '"Palatino Linotype", Palatino, Georgia, "Times New Roman", Times, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'rockwell' => array(
				'name' => 'Rockwell',
				'family' => 'Rockwell, "Arial Black", "Arial Bold", Arial, sans-serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => false
			),
			'allan' => array(
				'name' => 'Allan:bold',
				'family' => '"Allan", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'aclonica' => array(
				'name' => 'Aclonica',
				'family' => '"Aclonica", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'allerta' => array(
				'name' => 'Allerta',
				'family' => '"Allerta", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'allertastencil' => array(
				'name' => 'Allerta+Stencil',
				'family' => '"Allerta Stencil", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'amaranth' => array(
				'name' => 'Amaranth',
				'family' => '"Amaranth", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'annieuseyourtelescope' => array(
				'name' => 'Annie+Use+Your+Telescopel',
				'family' => '"Annie Use Your Telescope", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'anonymouspro' => array(
				'name' => 'Anonymous+Pro',
				'family' => '"Anonymous Pro", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'anton' => array(
				'name' => 'Anton',
				'family' => '"Anton", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'architectsdaughter' => array(
				'name' => 'Architects+Daughter',
				'family' => '"Architects Daughter", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'arimo' => array(
				'name' => 'Arimo',
				'family' => '"Arimo", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'artifika' => array(
				'name' => 'Artifika',
				'family' => '"Artifika", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'arvo' => array(
				'name' => 'Arvo',
				'family' => '"Arvo", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'astloch' => array(
				'name' => 'Astloch',
				'family' => '"Astloch", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'bangers' => array(
				'name' => 'Bangers',
				'family' => '"Bangers", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'bentham' => array(
				'name' => 'Bentham',
				'family' => '"Bentham", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'bevan' => array(
				'name' => 'Bevan',
				'family' => '"Bevan", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'bigshotone' => array(
				'name' => 'Bigshot+One',
				'family' => '"Bigshot One", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'brawler' => array(
				'name' => 'Brawler',
				'family' => '"Brawler", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'buda' => array(
				'name' => 'Buda:light',
				'family' => '"Buda", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'cabin' => array(
				'name' => 'Cabin:bold',
				'family' => '"Cabin", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'cabinsketch' => array(
				'name' => 'Cabin+Sketch:bold',
				'family' => '"Cabin Sketch", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'calligraffitti' => array(
				'name' => 'Calligraffitti',
				'family' => '"Calligraffitti", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'candal' => array(
				'name' => 'Candal',
				'family' => '"Candal", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'cantarell' => array(
				'name' => 'Cantarell',
				'family' => '"Cantarell", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'cardo' => array(
				'name' => 'Cardo',
				'family' => '"Cardo", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'cherrycreamsoda' => array(
				'name' => 'Cherry+Cream+Soda',
				'family' => '"Cherry Cream Soda", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'chewy' => array(
				'name' => 'Chewy',
				'family' => '"Chewy", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'coda' => array(
				'name' => 'Coda:800',
				'family' => '"Coda", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'comingsoon' => array(
				'name' => 'Coming+Soon',
				'family' => '"Coming Soon", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'copse' => array(
				'name' => 'Copse',
				'family' => '"Copse", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'corben' => array(
				'name' => 'Corben:bold',
				'family' => '"Corben", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'cousine' => array(
				'name' => 'Cousine',
				'family' => '"Cousine", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'coveredbyyourgrace' => array(
				'name' => 'Covered+By+Your+Grace',
				'family' => '"Covered By Your Grace", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'craftygirls' => array(
				'name' => 'Crafty+Girls',
				'family' => '"Crafty Girls", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'crimsontext' => array(
				'name' => 'Crimson+Text',
				'family' => '"Crimson Text", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'crushed' => array(
				'name' => 'Crushed',
				'family' => '"Crushed", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'cuprum' => array(
				'name' => 'Cuprum',
				'family' => '"Cuprum", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'droidsans' => array(
				'name' => 'Droid+Sans',
				'family' => '"Droid Sans", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'droidsansmono' => array(
				'name' => 'Droid+Sans+Mono',
				'family' => '"Droid Sans Mono", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'droidserif' => array(
				'name' => 'Droid+Serif',
				'family' => '"Droid Serif", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'fontdinerswanky' => array(
				'name' => 'Fontdiner+Swanky',
				'family' => '"Fontdiner Swanky", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'geo' => array(
				'name' => 'Geo',
				'family' => '"Geo", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'gruppo' => array(
				'name' => 'Gruppo',
				'family' => '"Gruppo", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'homemadeapple' => array(
				'name' => 'Homemade+Apple',
				'family' => '"Homemade Apple", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'imfellenglish' => array(
				'name' => 'IM+Fell+English',
				'family' => '"IM Fell English", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'inconsolata' => array(
				'name' => 'Inconsolata',
				'family' => '"Inconsolata", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'irishgrowler' => array(
				'name' => 'Irish+Growler',
				'family' => '"Irish Growler", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'josefinsansstdlight' => array(
				'name' => 'Josefin+Sans+Std+Light',
				'family' => '"Josefin Sans Std Light", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'josefinslab' => array(
				'name' => 'Josefin+Slab',
				'family' => '"Josefin Slab", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'justanotherhand' => array(
				'name' => 'Just+Another+Hand',
				'family' => '"Just Another Hand", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'justmeagaindownhere' => array(
				'name' => 'Just+Me+Again+Down+Here',
				'family' => '"Just Me Again Down Here", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'kenia' => array(
				'name' => 'Kenia',
				'family' => '"Kenia", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'kranky' => array(
				'name' => 'Kranky',
				'family' => '"Kranky", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'kristi' => array(
				'name' => 'Kristi',
				'family' => '"Kristi", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'lato' => array(
				'name' => 'Lato',
				'family' => '"Lato", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'lekton' => array(
				'name' => 'Lekton',
				'family' => '"Lekton", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'lobster' => array(
				'name' => 'Lobster',
				'family' => '"Lobster", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'luckiestguy' => array(
				'name' => 'Luckiest+Guy',
				'family' => '"Luckiest Guy", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'merriweather' => array(
				'name' => 'Merriweather',
				'family' => '"Merriweather", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'molengo' => array(
				'name' => 'Molengo',
				'family' => '"Molengo", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'mountainsofchristmas' => array(
				'name' => 'Mountains+of+Christmas',
				'family' => '"Mountains of Christmas", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'neucha' => array(
				'name' => 'Neucha',
				'family' => '"Neucha", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'neuton' => array(
				'name' => 'Neuton',
				'family' => '"Neuton", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'nobile' => array(
				'name' => 'Nobile',
				'family' => '"Nobile", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'oflsortsmillgoudytt' => array(
				'name' => 'OFL+Sorts+Mill+Goudy+TT',
				'family' => '"OFL Sorts Mill Goudy TT", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'oldstandardtt' => array(
				'name' => 'Old+Standard+TT',
				'family' => '"Old Standard TT", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'orbitron' => array(
				'name' => 'Orbitron',
				'family' => '"Orbitron", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'ptsans' => array(
				'name' => 'PT+Sans',
				'family' => '"PT Sans", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'permanentmarker' => array(
				'name' => 'Permanent+Marker',
				'family' => '"Permanent Marker", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'philosopher' => array(
				'name' => 'Philosopher',
				'family' => '"Philosopher", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'puritan' => array(
				'name' => 'Puritan',
				'family' => '"Puritan", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'raleway' => array(
				'name' => 'Raleway:100',
				'family' => '"Raleway", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'reeniebeanie' => array(
				'name' => 'Reenie+Beanie',
				'family' => '"Reenie Beanie", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'rocksalt' => array(
				'name' => 'Rock+Salt',
				'family' => '"Rock Salt", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'schoolbell' => array(
				'name' => 'Schoolbell',
				'family' => '"Schoolbell", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'slackey' => array(
				'name' => 'Slackey',
				'family' => '"Slackey", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'sniglet' => array(
				'name' => 'Sniglet:800',
				'family' => '"Sniglet", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'sunshiney' => array(
				'name' => 'Sunshiney',
				'family' => '"Sunshiney", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'syncopate' => array(
				'name' => 'Syncopate',
				'family' => '"Syncopate", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'tangerine' => array(
				'name' => 'Tangerine',
				'family' => '"Tangerine", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'tinos' => array(
				'name' => 'Tinos',
				'family' => '"Tinos", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'ubuntu' => array(
				'name' => 'Ubuntu',
				'family' => '"Ubuntu", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'unifrakturCook' => array(
				'name' => 'UnifrakturCook:bold',
				'family' => '"UnifrakturCook", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'unifrakturmaguntia' => array(
				'name' => 'UnifrakturMaguntia',
				'family' => '"UnifrakturMaguntia", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'unkempt' => array(
				'name' => 'Unkempt',
				'family' => '"Unkempt", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'vibur' => array(
				'name' => 'Vibur',
				'family' => '"Vibur", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'vollkorn' => array(
				'name' => 'Vollkorn',
				'family' => '"Vollkorn", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'walterturncoat' => array(
				'name' => 'Walter+Turncoat',
				'family' => '"Walter Turncoat", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			),
			'yanonekaffeesatz' => array(
				'name' => 'Yanone+Kaffeesatz',
				'family' => '"Yanone Kaffeesatz", arial, serif',
				'web_safe' => false,
				'monospace' => false,
				'google' => true
			)
		);
	}
}

function bizz_get_fonts() {
	$all_fonts = new Fonts;
	$all_fonts->set_fonts();
	
	return apply_filters ( 'bizz_design_fonts', $all_fonts->fonts );
}

