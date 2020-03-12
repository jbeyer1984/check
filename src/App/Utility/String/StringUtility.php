<?php


namespace Check\App\Utility\String;


use Exception;

class StringUtility
{
    /**
     * @param string $identifier
     * @param string $cmd
     * @return string
     */
    public static function extractBetweenIdentifier(string $identifier, string $cmd): string
    {
        $posScale = strpos($cmd, $identifier);
        if (false === $posScale) {
            return '';
        }
        $exploded = explode($identifier, $cmd);
        $exploded = array_filter($exploded, function ($entry) {
            return !empty($entry);
        });
        $statement = $exploded[1];

        return $statement;
    }

    /**
     * @param string $startIdentifier
     * @param string $endIdentifier
     * @param string $cmd
     * @return string
     * @throws Exception
     */
    public static function extractBetweenIdentifierPair(string $startIdentifier, string $endIdentifier, string $cmd)
    {
        $posStart = strpos($cmd, $startIdentifier);
        if (false === $posStart) {
            return '';
        }
        $cmd = substr($cmd, $posStart+1);
        $posEnd = strpos($cmd, $endIdentifier);
        if (false === $posEnd) {
            throw new Exception($cmd . ' should have end identifier: ' . $endIdentifier);
        }

        return substr($cmd, 0, $posEnd);
    }

    /**
     * @param string $string
     * @param string $append
     * @return string
     */
    public static function appendedOneTime(string $string, string $append)
    {
        if (false !== strpos($string, $append)) {
            return $string;
        }

        return $string . $append;
    }
}