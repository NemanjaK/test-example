<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'TableOutputBuilder.php';

class TableOutputBuilderTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException BadFunctionCallException
     */
    public function testEmptyArray() {
        $inputArray = array();
        $builder = new TableOutputBuilder($inputArray);
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testBadTableRepresentation() {
        $inputArray =     array(
            'Element' => 'Water',
            'Likes' => 'Dancing',
            'Name' => 'Blum',
            'Color' => 'Pink'
        );
        $builder = new TableOutputBuilder($inputArray);
    }

    public function testSuccessOutput() {
        $inputArray = array(
            array(
                'Name' => 'Trixie',
                'Color' => 'Green',
                'Element' => 'Earth',
                'Likes' => 'Flowers'
            ),
            array(
                'Name' => 'Tinkerbell',
                'Element' => 'Air',
                'Likes' => 'Singning',
                'Color' => 'Blue'
            ),
            array(
                'Element' => 'Water',
                'Likes' => 'Dancing',
                'Name' => 'Blum',
                'Color' => 'Pink'
            ),
        );

        $builder = new TableOutputBuilder($inputArray);
        $builder->flush();

        $this->expectOutputString("+------------+-------+---------+----------+
| Name       | Color | Element | Likes    |
+------------+-------+---------+----------+
| Trixie     | Green | Earth   | Flowers  |
+------------+-------+---------+----------+
| Tinkerbell | Blue  | Air     | Singning |
+------------+-------+---------+----------+
| Blum       | Pink  | Water   | Dancing  |
+------------+-------+---------+----------+
");
    }

}
 