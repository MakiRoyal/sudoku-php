<?php

define('nl', '\n');

$sqRows = 3;
$sqCols = 3;

$s = '0;4;0;0;5;3;1;0;2;2;0;8;1;0;0;7;0;0;5;0;1;4;2;0;6;0;0;8;1;4;0;3;0;2;0;7;0;6;0;2;0;5;0;1;9;0;5;0;7;4;0;0;6;3;0;0;0;0;7;4;5;8;1;1;8;5;9;0;2;0;0;0;4;0;3;0;0;8;0;2;6';

solve(str_replace(';', '', $s), $sqRows, $sqCols);

function solve($s, $sqRows, $sqCols)
{
    global $globalPossibles, $squareRows, $squareCols;
    $squareRows = $sqRows;
    $squareCols = $sqCols;

    $board = array();

    for ($i = 0; $i < 81; $i++) {
        $board[] = (int) $s[$i];
    }

    $globalPossibles = array();

    for ($i = 0; $i <= 9; $i++) {
        $globalPossibles[] = $i;
    }


    function printBoard(&$board)
    {
        for ($i = 0; $i < 81; $i++) {
            echo $board[$i] . ' ';
            if (($i + 1) % 9 == 0) {
                echo nl;
            }
        }
    }

    printBoard($board);

    function getPossibles($grid, $row, $col)
    {
        $possibles = array();

        // If the cell is already filled, return an empty array
        if (isset($grid[$row][$col]) != 0) {
            return $possibles;
        }

        // Loop through possible values (1-9) and check if they are valid for the cell
        for ($num = 1; $num <= 9; $num++) {
            // Check row
            $valid_row = true;
            for ($i = 0; $i < 9; $i++) {
                if (isset($grid[$row][$i]) == $num) {
                    $valid_row = false;
                    break;
                }
            }
            if (!$valid_row) {
                continue;
            }

            // Check column
            $valid_col = true;
            for ($i = 0; $i < 9; $i++) {
                if (isset($grid[$i][$col]) == $num) {
                    $valid_col = false;
                    break;
                }
            }
            if (!$valid_col) {
                continue;
            }

            // Check box
            $valid_box = true;
            $box_row = floor($row / 3) * 3;
            $box_col = floor($col / 3) * 3;
            for ($i = $box_row; $i < $box_row + 3; $i++) {
                for ($j = $box_col; $j < $box_col + 3; $j++) {
                    if (isset($grid[$i][$j]) == $num) {
                        $valid_box = false;
                        break 2;
                    }
                }
            }
            if (!$valid_box) {
                continue;
            }

            // If the value is valid, add it to the possibles array
            $possibles[] = $num;
        }

        return $possibles;
    }


    function solveBoard(&$board, $i, $j)
    {
        if ($i == 9) {
            echo ('solved' . nl);
            printBoard($board);
            return true;
        }

        $nextJ = ($j + 1) % 9;
        $nextI = ($nextJ == 0) ? $i + 1 : $i;

        if ($board[$i * 9 + $j] != 0) {
            return solveBoard($board, $nextI, $nextJ);
        }

        $possibles = getPossibles($board, $i, $j);
        foreach ($possibles as $possible) {
            $board[$i * 9 + $j] = $possible;
            if (solveBoard($board, $nextI, $nextJ)) {
                return true;
            }
        }
        $board[$i * 9 + $j] = 0;
        return false;
    }

    solveBoard($board, 0, 0);
}
