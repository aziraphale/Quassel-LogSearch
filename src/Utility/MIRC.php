<?php

namespace QuasselLogSearch\Utility;

use QuasselLogSearch\Utility\MIRC\MircLexer;
use QuasselLogSearch\Utility\MIRC\MircGrammar;
use Dissect\Parser\LALR1\Parser;

class MIRC
{
    public static function parseToHtml($string)
    {
        $lexer = new MircLexer();
        $parser = new Parser(new MircGrammar());

        $stream = $lexer->lex($string);

        return $parser->parse($stream);
    }
}

