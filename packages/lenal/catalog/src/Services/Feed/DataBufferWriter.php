<?php

namespace lenal\catalog\Services\Feed;


class DataBufferWriter
{
    const BUFFER_MAXSIZE = 1024 * 1024;

    private $charset;
    private $buffer = '';
    /** @var \Illuminate\Contracts\Filesystem\Filesystem  */
    private $handle;

    public function __construct($path)
    {
        $this->handle = fopen($path, 'w+');
    }

    public function add($chunk)
    {
        if ($chunk) {
            if ($this->buffer) {
                $this->buffer .= "\n";
            }

            $this->buffer .= $chunk;

            if (strlen($this->buffer) > self::BUFFER_MAXSIZE) {
                $this->flush();
            }
        }
    }

    public function addWithTab($chunk, $count = 1)
    {
        $this->add(str_repeat("\t", $count) . $chunk);
    }

    public function flush()
    {
        if ($this->buffer) {
            fwrite($this->handle, $this->buffer);
            $this->buffer = '';
        }
    }

    public function close()
    {
        $this->flush();
        fclose($this->handle);
    }

    /**
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
}