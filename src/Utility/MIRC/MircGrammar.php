<?php

namespace QuasselLogSearch\Utility\MIRC;

use Dissect\Parser\Grammar;

class MircGrammar extends Grammar
{
    public function __construct()
    {
        $this('Bold')
            ->is('BOLD')
            ->call(function($_){
                return '<span style="font-weight:bold;">';
            });

        $this('Italic')
            ->is('ITALIC')
            ->call(function($_){
                return '<span style="font-style:italic;">';
            });

        $this('Underline')
            ->is('UNDERLINE')
            ->call(function($_){
                return '<span style="text-decoration:underline;">';
            });

        $this('Reset')
            ->is('RESET')
            ->call(function($_){
                return '</span>';
            });

        $this('ColourFgBg')
            ->is('COLOUR', 'COLOUR_NUMBER', 'COLOUR_SEPARATOR', 'COLOUR_NUMBER')
            ->call(function($_, $fg, $__, $bg){
                return '<span class="fg-'.$fg->getValue().' bg-'.$bg->getValue().'">';
            });

        $this('ColourFg')
            ->is('COLOUR', 'COLOUR_NUMBER')
            ->call(function($_, $fg){
                return '<span class="fg-'.$fg->getValue().'">';
            });

        $this('Colour')
            ->is('COLOUR')
            ->call(function($_){
                return '</span>';
            });

        $this('ColourStart')
            ->is('COLOUR_START')
            ->call(function($s){
                return '<span class="colour-'.$s->getValue().'">';
            });

        $this('ColourEnd')
            ->is('COLOUR_END')
            ->call(function($s){
                return '</span>';
            });

        $this('PlainText')
            ->is('TEXT')
            ->call(function($s){
                return $s->getValue();
            });

        $this('Everything')
            ->is('Bold', 'Everything')
            ->is('Italic', 'Everything')
            ->is('Underline', 'Everything')
            ->is('Reset', 'Everything')
            ->is('ColourFgBg', 'Everything')
            ->is('ColourFg', 'Everything')
            ->is('Colour', 'Everything')
            ->is('ColourStart', 'Everything')
            ->is('ColourEnd', 'Everything')
            ->is('PlainText', 'Everything')
//            ->call(function($a, $b){
//                $args = func_get_args();
//                $out = join('', $args);
//                return $out;
//            })
            ->is()
            ->call(function(/*$a, $b*/){
                $out = '';
                $args = func_get_args();
                $out = join('', $args);
                return $out;
            })
            ;

        $this->operators('BOLD', 'ITALIC', 'UNDERLINE', 'RESET', 'COLOUR_START', 'COLOUR_END')->right();

//        $this->start('PlainText');
        $this->start('Everything');
    }
}

