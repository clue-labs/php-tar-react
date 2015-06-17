<?php

class Encoder implements ReadableStream
{
    private $remaining = 0;

    public function prepareWritableStream($header)
    {
        // TODO: make sure we're not exceeding the expected data
        // TODO: automatically append padding

        $this->writeHeader($header);

        $writable = new WritableStream();
        // buffer some bytes, handle backpressure
    }

    public function writeContents($header, $string)
    {
        $stream = $this->prepareWritableStream($header);
        $stream->end($string);
    }

    public function writeStream($header, ReadableStream $stream)
    {
        $out = $this->prepareWritableStream($header);
        $stream->pipe($out);
    }

    private function writeHeader($header)
    {

    }

    // TODO: soft-close not defined here?
    public function end()
    {

    }

    public function close()
    {

    }

    private function write($data)
    {
        $this->emit('data', array($data, $this));
    }
}
