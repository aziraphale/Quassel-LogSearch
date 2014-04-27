<?php

namespace QuasselLogSearch\Utility\MIRC;

use Dissect\Lexer\StatefulLexer;

class MircLexer extends StatefulLexer
{
    const MIRC_INDICATOR_BOLD       = "\x02";
    const MIRC_INDICATOR_NORMAL     = "\x0F";
    const MIRC_INDICATOR_ITALIC     = "\x1D";
    const MIRC_INDICATOR_UNDERLINE  = "\x1F";
    const MIRC_INDICATOR_COLOUR     = "\x03";

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
        $this->state('plaintext')
             ->token('BOLD', self::MIRC_INDICATOR_BOLD)
             ->token('ITALIC', self::MIRC_INDICATOR_ITALIC)
             ->token('UNDERLINE', self::MIRC_INDICATOR_UNDERLINE)
             ->token('RESET', self::MIRC_INDICATOR_NORMAL)
             ->token('COLOUR', self::MIRC_INDICATOR_COLOUR)
               ->action('colourcode')
             ->regex('TEXT',
                sprintf(
                    '/^[^%s%s%s%s%s]*/',
                    self::MIRC_INDICATOR_BOLD,
                    self::MIRC_INDICATOR_ITALIC,
                    self::MIRC_INDICATOR_UNDERLINE,
                    self::MIRC_INDICATOR_NORMAL,
                    self::MIRC_INDICATOR_COLOUR
                ))
//             ->regex('TEXT', '/^./')
             ;

        $this->state('colourcode')
             ->regex('COLOUR_NUMBER', '/^0|01|1[0-4]?|0?[2-9]')
             ->token('COLOUR_SEPARATOR', ',')
             ->regex('COLOUR_END', '/^(?![0-9,])')
               ->action(StatefulLexer::POP_STATE);

        $this->start('plaintext');
    }
}

