<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc/TableOutputBuilder.php';

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

try {
    $builder = new TableOutputBuilder($inputArray);
    $builder->setColorMode(true);
    $builder->setColumnColor('Name', OutputBuilderColors::GREEN);
    $builder->setColumnColor('Color', OutputBuilderColors::BLUE);
    $builder->setColumnColor('Element', OutputBuilderColors::CYAN);
    $builder->setColumnColor('Likes', OutputBuilderColors::RED);
    $builder->flush();
} catch (BadFunctionCallException $e) {
    print_r("Sorry, there was an error! Message follows: " . $e->getMessage() . PHP_EOL);
} catch (Exception $e) {
    print_r("General exception occurred. Message follows: " . $e->getMessage() . PHP_EOL);
}