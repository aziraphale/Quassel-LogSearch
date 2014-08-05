<?php

namespace QuasselLogSearch\Utility;

/**
 * This class shamelessly ported from the QuasselDroid NicksplitHelper class!
 *
 * @package QuasselLogSearch\Utility
 * @link https://github.com/sandsmark/QuasselDroid/blob/master/QuasselDroid/src/main/java/com/iskrembilen/quasseldroid/util/NetsplitHelper.java
 */
class NetsplitHelper
{
    const MAX_NETSPLIT_NICKS = 15;

    private $nicks = array();
    private $sideOne;
    private $sideTwo;

    public function __construct($netsplitString)
    {
        $splitString = explode('#:#', $netsplitString);

        $sides = array_shift($splitString);
        if (strpos($sides, ' ') !== false) {
            list($this->sideOne, $this->sideTwo) = explode(' ', $sides);
        }

        foreach ($splitString as $splitNick) {
            if (strpos($splitNick, '!') !== false) {
                $this->nicks[] = strstr($splitNick, '!', true);
            } else {
                $this->nicks[] = $splitNick;
            }
        }
    }

    public function getNicks()
    {
        return $this->nicks;
    }

    public function getSideOne()
    {
        return $this->sideOne;
    }

    public function getSideTwo()
    {
        return $this->sideTwo;
    }

    public function formatJoinMessage()
    {
        return sprintf(
            "Netsplit between %s and %s ended. Users joined: %s",
            $this->sideOne,
            $this->sideTwo,
            $this->formatNickList()
        );
    }

    public function formatQuitMessage()
    {
        return sprintf(
            "Netsplit between %s and %s. Users quit: %s",
            $this->sideOne,
            $this->sideTwo,
            $this->formatNickList()
        );
    }

    private function formatNickList()
    {
        $nicks = $this->nicks;
        $netsplitNickCount = count($nicks);

        if ($netsplitNickCount > self::MAX_NETSPLIT_NICKS) {
            $nicks = array_slice($nicks, 0, self::MAX_NETSPLIT_NICKS);
        }

        $nickList = join(', ', $nicks);

        if ($netsplitNickCount > self::MAX_NETSPLIT_NICKS) {
            $nickList .= sprintf(" (%d more)", ($netsplitNickCount - self::MAX_NETSPLIT_NICKS));
        }

        return $nickList;
    }
}
