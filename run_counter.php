<?php

$lockFilename = 'counter.lock';
$counterFilename = './counter.txt';

while (!checkAvailable($lockFilename)) {
    usleep(2000);
}

lock($lockFilename);
countFunc($counterFilename);
unlock($lockFilename);

echo PHP_EOL.'done';


function countFunc($counterFilename)
{
    $count = 0;
    if (file_exists($counterFilename)) {
        $count = file_get_contents($counterFilename);
    }
    $count++;
    file_put_contents($counterFilename, $count);
}

function checkAvailable($lockFilename)
{
    if (file_exists($lockFilename)) {
        return false;
    }

    return true;
}

function lock($lockFilename)
{
    $h = fopen($lockFilename, 'a');
    fclose($h);
}

function unlock($lockFilename)
{
    unlink($lockFilename);
}
