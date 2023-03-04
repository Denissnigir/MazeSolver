<?php

class MazeSolver {

    private $maze; // двумерный массив лабиринта
    private $rows; // кол-во строк в лабиринте
    private $cols; // кол-во столбцов в лабиринте

    public function __construct($maze) {
        $this->maze = $maze;
        $this->rows = count($maze);
        $this->cols = count($maze[0]);
    }

    public function solve($start, $end) {
        $dist = array(); // массив расстояний от стартовой позиции до каждой клетки
        $visited = array(); // массив посещённых клеток
        // заполнение массива значениями по умолчанию
        for ($i = 0; $i < $this->rows; $i++) {
            $dist[$i] = array_fill(0, $this->cols, INF);
            $visited[$i] = array_fill(0, $this->cols, false);
        }
        $dist[$start[0]][$start[1]] = 0; // расстояние от начальной точки до самой себя = 0
        $queue = array($start); // очередь для поиска
        while (!empty($queue)) {
            $curr = array_shift($queue); // извлечь текущую клетку из очереди
            $row = $curr[0];                    // номер строки текущей клетки
            $col = $curr[1];                    // номер столбца текущей клетки
            $visited[$row][$col] = true;        // пометить текущую клетку как посещенную
            // массив соседей
            $neighbors = array(
                array($row - 1, $col),
                array($row, $col + 1),
                array($row + 1, $col),
                array($row, $col - 1)
            );
            foreach ($neighbors as $neighbor) {
                $nrow = $neighbor[0]; // строка соседа
                $ncol = $neighbor[1]; // столбец соседа
                // проверка, что сосед находится в пределах лабиринта, еще не посещен,
                // не является стеной, и расстояние до соседа меньше, чем расстояние
                // до текущей клетки + стоимость перемещения
                if ($nrow >= 0 && $nrow < $this->rows &&
                    $ncol >= 0 && $ncol < $this->cols &&
                    !$visited[$nrow][$ncol] &&
                    $this->maze[$nrow][$ncol] != 0 &&
                    $dist[$nrow][$ncol] > $dist[$row][$col] + $this->maze[$nrow][$ncol]) {
                    $dist[$nrow][$ncol] = $dist[$row][$col] + $this->maze[$nrow][$ncol];
                    $queue[] = array($nrow, $ncol); // заносим текущую точку в очередь
                }
            }
        }
        return $dist[$end[0]][$end[1]];
    }

}

// Пример использования
$maze = array(
    array(1, 1, 2, 0, 0, 1),
    array(3, 0, 2, 1, 0, 3),
    array(1, 0, 0, 1, 1, 5),
    array(1, 8, 6, 1, 0, 0),
    array(0, 0, 1, 9, 1, 1)
);

$solver = new MazeSolver($maze);
$start = array(0, 0);
$end = array(4, 4);
$shortestPath = $solver->solve($start, $end);
echo "Кратчайший путь от ($start[0], $start[1]) до ($end[0], $end[1]): $shortestPath шагов.";