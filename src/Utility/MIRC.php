<?php

namespace QuasselLogSearch\Utility;

/**
 * Code partly "inspired" by the QuasselDroid parseStyleCodes() method - which I wrote half of, anyway...
 * @see QuasselDroid/src/main/java/com/iskrembilen/quasseldroid/util/MessageUtil.java
 */
class MIRC
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

    const REGEX_COLOUR_NUMBER = '(?:0|01|1[0-4]?|0?[2-9])';
    /**
     * This is essentially a constant, but is defined at runtime for the sake of code readability (see below)
     */
    private static $codeRegex;

    private static $defaultAsHtmlConfig = array(
        'class.all'                   => 'mirc-formatted',
        'class.bold'                  => 'mirc-bold',
        'class.italic'                => 'mirc-italic',
        'class.underline'             => 'mirc-underline',
        'class.colour'                => 'mirc-colour',
        'class.colour.fg.white'       => 'mirc-colour-fg-white',
        'class.colour.fg.black'       => 'mirc-colour-fg-black',
        'class.colour.fg.blue'        => 'mirc-colour-fg-blue',
        'class.colour.fg.green'       => 'mirc-colour-fg-green',
        'class.colour.fg.red'         => 'mirc-colour-fg-red',
        'class.colour.fg.brown'       => 'mirc-colour-fg-brown',
        'class.colour.fg.purple'      => 'mirc-colour-fg-purple',
        'class.colour.fg.orange'      => 'mirc-colour-fg-orange',
        'class.colour.fg.yellow'      => 'mirc-colour-fg-yellow',
        'class.colour.fg.light-green' => 'mirc-colour-fg-light-green',
        'class.colour.fg.teal'        => 'mirc-colour-fg-teal',
        'class.colour.fg.light-cyan'  => 'mirc-colour-fg-light-cyan',
        'class.colour.fg.light-blue'  => 'mirc-colour-fg-light-blue',
        'class.colour.fg.pink'        => 'mirc-colour-fg-pink',
        'class.colour.fg.grey'        => 'mirc-colour-fg-grey',
        'class.colour.bg.white'       => 'mirc-colour-bg-white',
        'class.colour.bg.black'       => 'mirc-colour-bg-black',
        'class.colour.bg.blue'        => 'mirc-colour-bg-blue',
        'class.colour.bg.green'       => 'mirc-colour-bg-green',
        'class.colour.bg.red'         => 'mirc-colour-bg-red',
        'class.colour.bg.brown'       => 'mirc-colour-bg-brown',
        'class.colour.bg.purple'      => 'mirc-colour-bg-purple',
        'class.colour.bg.orange'      => 'mirc-colour-bg-orange',
        'class.colour.bg.yellow'      => 'mirc-colour-bg-yellow',
        'class.colour.bg.light-green' => 'mirc-colour-bg-light-green',
        'class.colour.bg.teal'        => 'mirc-colour-bg-teal',
        'class.colour.bg.light-cyan'  => 'mirc-colour-bg-light-cyan',
        'class.colour.bg.light-blue'  => 'mirc-colour-bg-light-blue',
        'class.colour.bg.pink'        => 'mirc-colour-bg-pink',
        'class.colour.bg.grey'        => 'mirc-colour-bg-grey',
    );

    private $config;

    private $input;
    private $output;
    private $offset = 0;

    private $tagOpen = false;
    private $isBold = false;
    private $isItalic = false;
    private $isUnderline = false;
    private $colourForeground = false;
    private $colourBackground = false;

    public function __construct($string, array $config = array())
    {
        $this->input = $string;
        $this->config = array_merge(self::$defaultAsHtmlConfig, $config);

        if (!isset(self::$codeRegex)) {
            // We define this at runtime purely for the sake of the code not being a nightmare. We /could/ do some kind
            //  of pre-compilation step for this instead, but I don't think the performance hit of doing it at runtime
            //  is sufficient to justify the developer hassle of having that extra step
            self::$codeRegex = sprintf(
                '/(?<code_format>[%s%s%s%s])|(?<code_colour>%s)(?:(?<colour_fg>%s)(?:,(?<colour_bg>%s))?)?/S',
                // The <code_format> character class
                self::MIRC_INDICATOR_BOLD,
                self::MIRC_INDICATOR_ITALIC,
                self::MIRC_INDICATOR_UNDERLINE,
                self::MIRC_INDICATOR_NORMAL,

                // For <code_colour>
                self::MIRC_INDICATOR_COLOUR,

                // For <colour_fg> and <colour_bg>
                self::REGEX_COLOUR_NUMBER,
                self::REGEX_COLOUR_NUMBER
            );
        }
    }

    private function openTag()
    {
        $cssClasses = array();

        $cssClasses[] = $this->config['class.all'];

        if ($this->isBold) {
            $cssClasses[] = $this->config['class.bold'];
        }

        if ($this->isItalic) {
            $cssClasses[] = $this->config['class.italic'];
        }

        if ($this->isUnderline) {
            $cssClasses[] = $this->config['class.underline'];
        }

        if ($this->colourForeground || $this->colourBackground) {
            $cssClasses[] = $this->config['class.colour'];

            if ($this->colourForeground) {
                switch ((int) $this->colourForeground) {
                    case self::MIRC_COLOUR_WHITE:
                        $cssClasses[] = $this->config['class.colour.fg.white'];
                        break;
                    case self::MIRC_COLOUR_BLACK:
                        $cssClasses[] = $this->config['class.colour.fg.black'];
                        break;
                    case self::MIRC_COLOUR_BLUE:
                        $cssClasses[] = $this->config['class.colour.fg.blue'];
                        break;
                    case self::MIRC_COLOUR_GREEN:
                        $cssClasses[] = $this->config['class.colour.fg.green'];
                        break;
                    case self::MIRC_COLOUR_RED:
                        $cssClasses[] = $this->config['class.colour.fg.red'];
                        break;
                    case self::MIRC_COLOUR_BROWN:
                        $cssClasses[] = $this->config['class.colour.fg.brown'];
                        break;
                    case self::MIRC_COLOUR_PURPLE:
                        $cssClasses[] = $this->config['class.colour.fg.purple'];
                        break;
                    case self::MIRC_COLOUR_ORANGE:
                        $cssClasses[] = $this->config['class.colour.fg.orange'];
                        break;
                    case self::MIRC_COLOUR_YELLOW:
                        $cssClasses[] = $this->config['class.colour.fg.yellow'];
                        break;
                    case self::MIRC_COLOUR_LIGHT_GREEN:
                        $cssClasses[] = $this->config['class.colour.fg.light-green'];
                        break;
                    case self::MIRC_COLOUR_TEAL:
                        $cssClasses[] = $this->config['class.colour.fg.teal'];
                        break;
                    case self::MIRC_COLOUR_LIGHT_CYAN:
                        $cssClasses[] = $this->config['class.colour.fg.light-cyan'];
                        break;
                    case self::MIRC_COLOUR_LIGHT_BLUE:
                        $cssClasses[] = $this->config['class.colour.fg.light-blue'];
                        break;
                    case self::MIRC_COLOUR_PINK:
                        $cssClasses[] = $this->config['class.colour.fg.pink'];
                        break;
                    case self::MIRC_COLOUR_GREY:
                        $cssClasses[] = $this->config['class.colour.fg.grey'];
                        break;
                }
            }

            if ($this->colourBackground) {
                switch ((int) $this->colourBackground) {
                    case self::MIRC_COLOUR_WHITE:
                        $cssClasses[] = $this->config['class.colour.bg.white'];
                        break;
                    case self::MIRC_COLOUR_BLACK:
                        $cssClasses[] = $this->config['class.colour.bg.black'];
                        break;
                    case self::MIRC_COLOUR_BLUE:
                        $cssClasses[] = $this->config['class.colour.bg.blue'];
                        break;
                    case self::MIRC_COLOUR_GREEN:
                        $cssClasses[] = $this->config['class.colour.bg.green'];
                        break;
                    case self::MIRC_COLOUR_RED:
                        $cssClasses[] = $this->config['class.colour.bg.red'];
                        break;
                    case self::MIRC_COLOUR_BROWN:
                        $cssClasses[] = $this->config['class.colour.bg.brown'];
                        break;
                    case self::MIRC_COLOUR_PURPLE:
                        $cssClasses[] = $this->config['class.colour.bg.purple'];
                        break;
                    case self::MIRC_COLOUR_ORANGE:
                        $cssClasses[] = $this->config['class.colour.bg.orange'];
                        break;
                    case self::MIRC_COLOUR_YELLOW:
                        $cssClasses[] = $this->config['class.colour.bg.yellow'];
                        break;
                    case self::MIRC_COLOUR_LIGHT_GREEN:
                        $cssClasses[] = $this->config['class.colour.bg.light-green'];
                        break;
                    case self::MIRC_COLOUR_TEAL:
                        $cssClasses[] = $this->config['class.colour.bg.teal'];
                        break;
                    case self::MIRC_COLOUR_LIGHT_CYAN:
                        $cssClasses[] = $this->config['class.colour.bg.light-cyan'];
                        break;
                    case self::MIRC_COLOUR_LIGHT_BLUE:
                        $cssClasses[] = $this->config['class.colour.bg.light-blue'];
                        break;
                    case self::MIRC_COLOUR_PINK:
                        $cssClasses[] = $this->config['class.colour.bg.pink'];
                        break;
                    case self::MIRC_COLOUR_GREY:
                        $cssClasses[] = $this->config['class.colour.bg.grey'];
                        break;
                }
            }
        }

        $this->output .= '<span class="' . join(' ', $cssClasses) . '">';
        $this->tagOpen = true;
    }

    private function closeTag()
    {
        $this->output .= '</span>';
        $this->tagOpen = false;
    }

    private function processFormatChange()
    {
        if ($this->tagOpen) {
            $this->closeTag();
        }
        if ($this->isBold || $this->isItalic || $this->isUnderline || $this->colourForeground || $this->colourBackground) {
            $this->openTag();
        }
    }

    private function doesInputContainAnyCodesAtAll()
    {
        return (
            strpos($this->input, self::MIRC_INDICATOR_BOLD)       !== false ||
            strpos($this->input, self::MIRC_INDICATOR_ITALIC)     !== false ||
            strpos($this->input, self::MIRC_INDICATOR_UNDERLINE)  !== false ||
            strpos($this->input, self::MIRC_INDICATOR_COLOUR)     !== false ||
            strpos($this->input, self::MIRC_INDICATOR_NORMAL)     !== false
        );
    }

    public function toHtml()
    {
        if (!$this->doesInputContainAnyCodesAtAll()) {
            return $this->input;
        }

        while (true) {
            if (preg_match(self::$codeRegex, $this->input, $matches, PREG_OFFSET_CAPTURE, $this->offset)) {
                // Found a new format code
                if ($matches[0][1] > $this->offset) {
                    // Our matched format code is NOT at the beginning of our search string (we skipped over some normal
                    //  text to get here), so output whatever was skipped
                    $skippedCharsCount = ($matches[0][1] - $this->offset);
                    $this->output .= substr($this->input, $this->offset, $skippedCharsCount);
                    $this->offset += $skippedCharsCount;
                }

                if (isset($matches['code_format']) && $matches['code_format'][1] >= 0) {
                    // Non-colour formatting code
                    switch ($matches['code_format'][0]) {
                        case self::MIRC_INDICATOR_BOLD:
                            $this->isBold = !$this->isBold;
                            break;
                        case self::MIRC_INDICATOR_ITALIC:
                            $this->isItalic = !$this->isItalic;
                            break;
                        case self::MIRC_INDICATOR_UNDERLINE:
                            $this->isUnderline = !$this->isUnderline;
                            break;
                        case self::MIRC_INDICATOR_NORMAL:
                            $this->isBold = false;
                            $this->isItalic = false;
                            $this->isUnderline = false;
                            $this->colourForeground = false;
                            $this->colourBackground = false;
                            break;
                    }
                } elseif (isset($matches['code_colour']) && $matches['code_colour'][1] >= 0) {
                    // A colour formatting code
                    if (isset($matches['colour_fg']) && $matches['colour_fg'][1] >= 0) {
                        // Actually specified a colour!
                        $this->colourForeground = $matches['colour_fg'][0];

                        if (isset($matches['colour_bg']) && $matches['colour_bg'][1] >= 0) {
                            // Got a background colour specified, too!
                            $this->colourBackground = $matches['colour_bg'][0];
                        }
                    } else {
                        // No colour specified - treat this as a colour-only "reset" token
                        $this->colourForeground = false;
                        $this->colourBackground = false;
                    }
                }

                $this->processFormatChange();
                $this->offset += strlen($matches[0][0]);
            } else {
                // Haven't found any more codes; just output the rest of the string and quit out
                $this->output .= substr($this->input, $this->offset);
                $this->offset = strlen($this->input);
                break;
            }
        }

        if ($this->tagOpen) {
            $this->closeTag();
        }

        return $this->output;
    }
}
