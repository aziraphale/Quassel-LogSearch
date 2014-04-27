<?php

namespace QuasselLogSearch\Utility\MIRC;

use Dissect\Lexer\SimpleLexer;

class MircSimpleLexer extends SimpleLexer
{
    const MIRC_INDICATOR_BOLD       = "\x02";
    const MIRC_INDICATOR_NORMAL     = "\x0F";
    const MIRC_INDICATOR_ITALIC     = "\x1D";
    const MIRC_INDICATOR_UNDERLINE  = "\x1F";
    const MIRC_INDICATOR_COLOUR     = "\x03";
    const MIRC_INDICATOR_COLOUR_NUM = "0|01|1[0-4]?|0?[2-9]";

    const MIRC_COLOUR_WHITE         = 0;
    const MIRC_COLOUR_BLACK         = 1;
    const MIRC_COLOUR_BLUE          = 2;
    const MIRC_COLOUR_GREEN         = 3;
    const MIRC_COLOUR_RED           = 4;
    const MIRC_COLOUR_BROWN         = 5;
    const MIRC_COLOUR_PURPLE        = 6;
    const MIRC_COLOUR_ORANGE        = 7;
    const MIRC_COLOUR_YELLOW        = 8;
    const MIRC_COLOUR_LIGHT_GREEN   = 9;
    const MIRC_COLOUR_TEAL          = 10;
    const MIRC_COLOUR_LIGHT_CYAN    = 11;
    const MIRC_COLOUR_LIGHT_BLUE    = 12;
    const MIRC_COLOUR_PINK          = 13;
    const MIRC_COLOUR_GREY          = 14;

    public function __construct()
    {

        $this->token('BOLD', self::MIRC_INDICATOR_BOLD)
             ->token('ITALIC', self::MIRC_INDICATOR_ITALIC)
             ->token('UNDERLINE', self::MIRC_INDICATOR_UNDERLINE)
             ->token('RESET', self::MIRC_INDICATOR_NORMAL)
             ->regex('COLOUR_START',
                sprintf(
                    '/^%s(%s)(?:,(%s))?/',
                    self::MIRC_INDICATOR_COLOUR,
                    self::MIRC_INDICATOR_COLOUR_NUM,
                    self::MIRC_INDICATOR_COLOUR_NUM
                ))
             ->regex('COLOUR_END',
                sprintf(
                    '/^%s(?!%s)/',
                    self::MIRC_INDICATOR_COLOUR,
                    self::MIRC_INDICATOR_COLOUR_NUM
                ))
             ->regex('TEXT',
                sprintf(
                    '/^[^%s%s%s%s%s]+/',
                    self::MIRC_INDICATOR_BOLD,
                    self::MIRC_INDICATOR_ITALIC,
                    self::MIRC_INDICATOR_UNDERLINE,
                    self::MIRC_INDICATOR_NORMAL,
                    self::MIRC_INDICATOR_COLOUR
                ))
             ;
    }
}

