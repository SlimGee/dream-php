<?php
namespace Lead;
/**
 *
 */
class Lexer
{
    private $stream;

    const OPERATORS = ['+','-','*','/'];

    const KEYWORDS = ['if','else','elif','end','for','each','as','print','def','[',']',':'];

    const COMPARISON = [
        '<',
        '>'
    ];
    private $lexing = false;

    public function __construct(Stream $stream,$lexing = false)
    {
        $this->stream = $stream;
        $this->lexing = $lexing;
    }

    public function lex()
    {
        foreach ($this->stream as $token) {
            if ($this->lexing) {
                if ($token == "\n") {
                    yield ['terminator',''];
                }
                elseif (in_array($token,self::OPERATORS)) {
                    yield ['operation', $token];
                }
                elseif (in_array($token,self::KEYWORDS)) {
                    yield [$token,''];
                }
                elseif(preg_match('/[0-9]/',$token)) {
                    yield ['number',$this->scan('/[0-9.]/')];
                }
                elseif (preg_match('/[_a-zA-Z]/',$token)) {
                    $sym = $this->scan('/[a-zA-Z0-9_]/');
                    if (in_array($sym,self::KEYWORDS)) {
                        yield [$sym,''];
                    }
                    else {
                        yield ['symbol',$sym];
                    }
                }
                elseif (preg_match('/(\')|(\")/',$token)) {
                    yield ['string',$this->upto($token)];
                }
                elseif ($token == "=") {
                    $tok = $this->scan('/=/');
                    if ($tok == "==") {
                        yield ['operation',$tok];
                    }
                    else {
                        yield[$token, ''];
                    }
                }
                elseif (preg_match('/(&)|(!)|(\|)/',$token)) {
                    yield ['operation',$this->scan('/(&)|(!)|(=)|(\|)/')];
                }
                elseif (in_array($token,self::COMPARISON)) {
                    yield ['operation',$this->scan('/(<)|(=)|(>)/')];
                }
                elseif ($token == '{') {
                    $this->lexing = true;
                    continue;
                }
                elseif ($token == '}') {
                    $this->lexing = false;
                    yield ['terminator',''];
                }
                elseif ($token == ',') {
                    yield [',',''];
                }
                elseif ($token == ".") {
                    yield ['.',''];
                }
                elseif ($token == ")") {
                    yield [')',''];
                }
            }
            elseif ($token == '{') {
                $this->lexing = true;
                $sym = $this->scan('/{|=/');
                if ($sym == '{=') {
                    yield ['echo',''];
                }
                else {
                    continue;
                }
            }
            elseif ($token == '}') {
                yield ['terminator',''];
                $this->lexing = false;
            }
            else
            {
                yield ['html',$this->html('{')];
                yield ['terminator',''];
            }

        }
    }

    public function scan($regex)
    {
        $ret = '';
        while ($this->stream->valid() && preg_match($regex,$this->stream->current()))
        {
            $ret .= $this->stream->current();
            $this->stream->next();
        }
        $this->stream->prev();
        return $ret;
    }

    public function upto($delim)
    {
        $ret = '';
        $this->stream->next();
        while ($this->stream->valid() && $this->stream->current() !== $delim)
        {
            $ret .= $this->stream->current();
            $this->stream->next();
        }
        return $ret;
    }

    public function html($delim)
    {
        $ret = '';
        while ($this->stream->valid() && $this->stream->current() !== $delim)
        {
            $ret .= $this->stream->current();
            $this->stream->next();
        }
        $this->stream->prev();
        return $ret;
    }
}
