<?php

class sudoku{
	private $sudoku = [];
	private $sudokuBoxes = [];
	private $possibleSolutions = [];

	public function __construct($sudoku){
		foreach( $sudoku as $row=>$rows ){
			foreach( $rows as $col=>$value ){
				$this->sudoku[$row][$col] = $value;

				if( $value == '' ){ continue; }
				$box = $this->getBox($row, $col);
				$this->sudokuBoxes[$box][] = $value;
			}
		}
	}

	public function solve(){
		$this->print();
		
		$s = microtime(1);
		$this->getCellsCandidates();

		$this->solveBacktrack();
		// $this->solveBacktrackHeuristic();

		$e = microtime(1);
		echo 'Time: '.($e-$s).PHP_EOL;
	}


	/* INI cell candidates function */
	protected function getBox($row, $col){
		$box = 3 * (ceil( ($row + 1) / 3 ) - 1) + ceil( ($col + 1) / 3 );
		return $box;
	}

	protected function getCellsCandidates(){
		$this->possibleSolutions = [];
		foreach( $this->sudoku as $row=>$rows ){
			foreach( $rows as $col=>$value ){
				if( $value != '' ){ continue; }
				$validNumbers = [1,2,3,4,5,6,7,8,9];

				$this->getRowCandidates($row,$validNumbers);
				$this->getColCandidates($col,$validNumbers);
				$this->getBoxCandidates($row,$col,$validNumbers);


				if( count($validNumbers) == 1 ){
					$number = array_shift($validNumbers);
					$this->sudoku[$row][$col] = $number;

					$box = $this->getBox($row, $col);
					$this->sudokuBoxes[$box][] = $number;

					return $this->getCellsCandidates();
				}


				if( !$validNumbers ){ continue; }

				$this->possibleSolutions[$row][$col] = $validNumbers;
			}
		}
	}

	private function getRowCandidates($row, &$validNumbers){
		$validNumbers = array_diff($validNumbers, $this->sudoku[$row]);
	}

	private function getColCandidates($col, &$validNumbers){
		$colNumbers = [];
		foreach( $this->sudoku as $row=>$rows ){
			if( $rows[$col] === '' ){ continue; }
			$colNumbers[] = $rows[$col];
		}
		$validNumbers = array_diff($validNumbers, $colNumbers);
	}

	private function getBoxCandidates($row, $col, &$validNumbers){
		$box = $this->getBox($row, $col);
		if( !isset($this->sudokuBoxes[$box]) ){ return; }

		$validNumbers = array_diff($validNumbers, $this->sudokuBoxes[$box]);
	}
	/* END cell candidates function */


	/* INI validation functions */
	protected function validateCell($row, $col){
		return $this->validateRow($row, $col) && $this->validateCol($row, $col) && $this->validateBox($row, $col);
	}

	private function validateRow($row, $col){
		$value = $this->sudoku[$row][$col];
		// if( $value === '' ){ return true; }

		$rowValuesCount = array_count_values( array_diff($this->sudoku[$row], ['']) );

		if( !isset($rowValuesCount[$value]) || $rowValuesCount[$value] > 1 ){ return false; }
		return true;	
	}

	private function validateCol($row, $col){
		$colNumbers = [];
		foreach( $this->sudoku as $rows ){
			$colNumbers[] = $rows[$col];
		}

		$value = $this->sudoku[$row][$col];
		// if( $value === '' ){ return true; }

		$colValuesCount = array_count_values( array_diff($colNumbers, ['']) );

		if( !isset($colValuesCount[$value]) || $colValuesCount[$value] > 1 ){ return false; }
		return true;
	}

	private function validateBox($row, $col){
		$value = $this->sudoku[$row][$col];

		$boxRow = floor($row / 3);
		$boxCol = floor($col / 3);

		for( $r = $boxRow * 3; $r < $boxRow * 3 + 3; $r++ ){
			for( $c = $boxCol * 3; $c < $boxCol * 3 + 3; $c++ ){
				if( ($r != $row || $c != $col) && $this->sudoku[$r][$c] == $value ){
					return false;
				}
			}
		}
		return true;
	}
	/* END validation functions */


	private function solveBacktrack($row = 0, $col = 0){
		if( $col > 8 ){
			$col = 0;
			if( ++$row > 8 ){
				return true;
			}
		}

		if( $this->sudoku[$row][$col] != '' ){
			if( !$this->validateCell($row, $col) ){
				return false;
			}
			return $this->solveBacktrack($row, $col + 1);
		}

		foreach( $this->possibleSolutions[$row][$col] as $solution ){
			$this->sudoku[$row][$col] = $solution;

			if( $this->validateCell($row, $col) ){
				if( $this->solveBacktrack($row, $col + 1) ){
					return true;
				}
			}
		}

		$this->sudoku[$row][$col] = '';
		return false;
	}

	/* This is not working */
	private function solveBacktrackHeuristic(){
		if( !$this->possibleSolutions ){ return; }

		$solutions = [];
		foreach( $this->possibleSolutions as $row=>$rows ){
			foreach( $rows as $col=>$values ){
				$solutions[$row.'#'.$col] = $values;
			}
		}

		uasort($solutions, function($a,$b){ return count($a) > count($b); });
		
		passthru('clear');
		$this->print();
		// foreach( $solutions as $k=>$s ){
			// echo '('.$k.') => '.implode(',', $s).PHP_EOL;
		// }
		// echo PHP_EOL;
		usleep(100000);

		foreach( $solutions as $cell=>$values ){
			list($row,$col) = explode('#', $cell);

			foreach( $values as $solution ){
				

				$this->sudoku[$row][$col] = $solution;

				if( $this->validateRow($row, $col) && $this->validateCol($row, $col) && $this->validateBox($row, $col) ){
					$this->getCellsCandidates();
					if( $this->solveBacktrackHeuristic() ){
						return true;
					}
				}
			}

			$this->sudoku[$row][$col] = '';
			$this->getCellsCandidates();

			return false;
		}
	}
	/* This is not working */

	public function print(){
		echo '========================================='.PHP_EOL;
		foreach( $this->sudoku as $row=>$rows ){
			echo '|| ';
			foreach( $rows as $col=>$value ){
				echo ($value !== '' ? $value : ' ');
				if( ($col + 1) % 3 == 0 ){
					if( $col == 8 ){ continue; }
					echo ' || ';
				} else {
					echo ' | ';
				}
			}
			echo ' ||'.PHP_EOL;
			if( ($row + 1) % 3 == 0 ){
				echo '========================================='.PHP_EOL;
			} else {
				echo '-----------------------------------------'.PHP_EOL;
			}
		}
	}
}