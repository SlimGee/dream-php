<?php
namespace Dream\Http;

use Throwable;
use RuntimeException;
use SplFileInfo;
use Psr\Http\Message\StreamInterface;

/**
 *
 */
class Stream implements StreamInterface
{
    protected $stream;

    protected $metadata;

    protected $info;

    /**
     * initialize stream at construt
     */
    public function __construct($input,$mode = Constants::MODE_READ)
    {
        $this->stream = fopen($input, $mode);
        $this->metadata = stream_get_meta_data($this->stream);
        $this->info = new SplFileInfo($input);
    }

    /**
     * Returns the current stream
     * @return resource The Stream;
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * Returns information about the current stream
     * @return SplFileInfo The Stream info
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString()
    {
        $this->rewind();
        return $this->getContents();
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close()
    {
        if ($this->stream) {
            fclose($this->stream);
        }
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
        return $this->close();
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize()
    {
        return $this->info->getSize();
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer
     * @throws \RuntimeException on error.
     */
    public function tell()
    {
        try {
            return ftell($this->stream);
        } catch (Throwable $e) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof()
    {
        return eof($this->stream);
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable()
    {
        return boolval($this->metadata['seekable']);
    }

    /**
     * Seek to a position in the stream.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws \RuntimeException on failure.
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        try {
            fseek($this->stream,$offset,$whence);
        } catch (Throwable $e) {
            throw new InvalidArgumentException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @see seek()
     * @link http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
     */
    public function rewind()
    {
        if (!rewind($this->stream)) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable()
    {
        return $this->info->isWritable();
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \RuntimeException on failure.
     */
    public function write($string)
    {
        if (!fwrite($this->stream, $string)) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable()
    {
        $this->info->isReadable();
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws RuntimeException if an error occurs.
     */
    public function read($length)
    {
        if (!fread($this->stream, $length)) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws \RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public function getContents()
    {
        ob_start();
        if (!fpassthru($this->stream)) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
        return ob_get_clean();
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata($key = null)
    {
        if ($key) {
            return $this->metadata[$key] ?? null;
        }
        return $this->metadata;
    }
}
