<?php
require('class.sudoku.php');

$sudokus = [
	[
		['8','','','','','','','',''],
		['','','3','6','','','','',''],
		['','7','','','9','','2','',''],
		['','5','','','','7','','',''],
		['','','','','4','5','7','',''],
		['','','','1','','','','3',''],
		['','','1','','','','','6','8'],
		['','','8','5','','','','1',''],
		['','9','','','','','4','',''],
	],
	[
		['5','3','','','7','','','',''],
		['6','','','1','9','5','','',''],
		['','9','8','','','','','6',''],
		['8','','','','6','','','','3'],
		['4','','','8','','3','','','1'],
		['7','','','','2','','','','6'],
		['','6','','','','','2','8',''],
		['','','','4','1','9','','','5'],
		['','','','','8','','','7','9'],
	],
	[
		['','','','','','','','',''],
		['1','2','','','','','','8','4'],
		['','3','','','','','','7',''],
		['','','4','','','','6','',''],
		['','','','2','','3','','',''],
		['','','5','','','','9','',''],
		['','','6','','9','','5','',''],
		['','7','','','','','','2',''],
		['','','','','5','','','',''],
	],
	[
		['','4','','','6','','1','3',''],
		['1','','','','','','','','9'],
		['','','9','','','2','','','5'],
		['','1','','6','','','5','8',''],
		['6','','3','8','','5','','','4'],
		['','8','','7','','','3','6',''],
		['','','1','','','8','','','6'],
		['7','','','','','','','','3'],
		['','3','','','4','','7','5',''],
	],
	[
		['','','','','','','','',''],
		['','','','','','3','','8','5'],
		['','','1','','2','','','',''],
		['','','','5','','7','','',''],
		['','','4','','','','1','',''],
		['','9','','','','','','',''],
		['5','','','','','','7','','3'],
		['','','2','','1','','','',''],
		['','','','','4','','','','9'],
	]
];


foreach( $sudokus as $test=>$sudoku ){
	echo 'Test '.$test.PHP_EOL;
	$s = new sudoku($sudoku);
	$s->solve();
	$s->print();
	echo PHP_EOL;
}