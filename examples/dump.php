<?php

use React\Stream\Stream;
use React\EventLoop\Factory;
use Clue\React\Tar\Decoder;
use React\Stream\BufferedSink;
use Clue\Hexdump\Hexdump;
use React\EventLoop\StreamSelectLoop;

require __DIR__ . '/../vendor/autoload.php';

$in = isset($argv[1]) ? $argv[1] : (__DIR__ . '/../tests/fixtures/alice-bob.tar');
echo 'Reading file "' . $in . '" (pass as argument to example)' . PHP_EOL;

// using the default loop does *not* work for file I/O
//$loop = Factory::create();
$loop = new StreamSelectLoop();

$stream = new Stream(fopen($in, 'r'), $loop);

$decoder = new Decoder();
$decoder->on('entry', function ($header, $file) {
    static $i = 0;
    echo 'FILE #' . ++$i . PHP_EOL;


    echo 'Received entry headers:' . PHP_EOL;
    var_dump($header);

    BufferedSink::createPromise($file)->then(function ($contents) {
        echo 'Received entry contents (' . strlen($contents) . ' bytes)' . PHP_EOL;

        $d = new Hexdump();
        echo $d->dump($contents) . PHP_EOL . PHP_EOL;
    });
});
$decoder->on('error', function ($error) {
    echo 'ERROR: ' . $error . PHP_EOL;
});
$decoder->on('close', function() {
    echo 'CLOSED' . PHP_EOL;
});

$stream->pipe($decoder);

$loop->run();
