<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "OutputBuilder.php";

class TableOutputBuilder extends OutputBuilder {

    /** @var array Keeps the columns of provided table */
    private $columns = array();

    /** @var array keeps the data of the provided table */
    private $data = array();

    /** @var array of colors for each column */
    private $colorScheme = array();

    private $tableWidthCell = array();

    private static $columnSeparator = '|';
    private static $rowSeparator = '-';
    private static $junction = '+';
    private static $hSpacing = 1;

    /** For supplied table initialize $columns and $data */
    public function __construct($table) {
        if(!is_array($table) || empty($table)) {
            throw new BadFunctionCallException("Provided table is not an array of rows");
        }

        $this->validateTable($table);

        $this->columns = array_keys($table[0]);

        foreach ($this->columns as $id) {
            $this->colorScheme[$id] = OutputBuilderColors::WHITE;
        }

        $this->data = $table;
        $this->initializeTableWidth();
    }

    /** This method validates if provided array is a representation of table
     * @param $array
     */
    private function validateTable($array) {
        // It can't be empty
        if(empty($array)) throw new BadMethodCallException("Array can't be empty!");

        // Root level keys must be integers (no assoc array)
        $keys = array_keys($array);
        foreach($keys as $val) {
            if (is_int($val) !== true) throw new BadMethodCallException("Invalid table representation - root must be regular array not assoc array (e.g. Map)!");
        }

        // Each row must have all columns
        $columns = array_keys($array[0]);
        foreach($array as $id => $row) {
            foreach($columns as $col) {
                if (!isset($row[$col])) throw new BadMethodCallException("Invalid table representation! Missing column [ $col ] in row #$id");
            }
        }
    }

    public function setColumnColor($column, $color) {
        if(!isset($this->colorScheme[$column])) {
            throw new BadFunctionCallException("Unknown column");
        }
        $this->colorScheme[$column] = $color;
    }

    public function initializeTableWidth() {
        $this->tableWidthCell = array();
        // Width necessary for table header
        foreach($this->columns as $id => $val) {
            $tmpWidth = strlen($val) + 2 * self::$hSpacing;
            $this->tableWidthCell[$id] = $tmpWidth;
        }

        // Width necessary for longest table row
        foreach($this->data as $row) {
            foreach($this->columns as $id => $key) {
                $tmpWidth = strlen($row[$key]) + 2 * self::$hSpacing;
                if($tmpWidth > $this->tableWidthCell[$id]) $this->tableWidthCell[$id] = $tmpWidth;
            }
        }

    }

    private function addRowLine() {
        // First print row line
        foreach ($this->columns as $key => $value) {
            if($key === 0) $this->concat(self::$junction);
            for($i = 0; $i < $this->tableWidthCell[$key]; $i++) {
                $this->concat(self::$rowSeparator);
            }
            $this->concat(self::$junction);
        }
        $this->concat(PHP_EOL);
    }

    private function addHeaderLine() {
        // Now header row
        foreach ($this->columns as $key => $value) {
            if($key === 0) $this->concat(self::$columnSeparator);
            $currentWidth = strlen(self::$hSpacing) + strlen($value);
            for($i = 0; $i < self::$hSpacing; $i++) { $this->concat(" "); }
            $this->setColor($this->colorScheme[$value]); $this->concat($value); $this->clearColor();
            for($i = 0; $i < ($this->tableWidthCell[$key] - $currentWidth); $i++) { $this->concat(" "); }
            $this->concat(self::$columnSeparator);
        }
        $this->concat(PHP_EOL);
    }

    private function addRow($line) {
        $this->concat(self::$columnSeparator);
        foreach($this->columns as $key => $column) {
            $currentWidth = strlen(self::$hSpacing) + strlen($line[$column]);
            for($i = 0; $i < self::$hSpacing; $i++) { $this->concat(" "); }
            $this->setColor($this->colorScheme[$column]); $this->concat($line[$column]); $this->clearColor();
            for($i = 0; $i < $this->tableWidthCell[$key] - $currentWidth; $i++) { $this->concat(" "); }
            $this->concat(self::$columnSeparator);
        }
        $this->concat(PHP_EOL);
    }

    public function flush() {
        $this->addRowLine();
        $this->addHeaderLine();
        $this->addRowLine();
        foreach($this->data as $line) {
            $this->addRow($line);
            parent::flush();
            $this->addRowLine();
            parent::flush();
        }

    }

} 