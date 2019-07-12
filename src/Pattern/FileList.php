<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Pattern;

// [imports]
use Ra5k\Salud\Pattern;
use Closure;


/**
 *
 *
 * @link https://git-scm.com/docs/gitignore
 */
final class FileList implements Pattern
{
    /**
     * @var array
     */
    private $includes;

    /**
     * @var array
     */
    private $excludes;

    /**
     * 
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->includes = [];
        $this->excludes = [];
        //
        foreach ($rules as $rule) {
            if (is_string($rule)) {
                $rule = trim($rule);
                if (substr($rule, 0, 1) == '!') {
                    $this->excludes[] = $this->rule(substr($rule, 1));
                } else {
                    $this->includes[] = $this->rule($rule);
                }
            }
        }
    }

    /**
     * @param string $subject
     * @return bool
     */
    public function test(string $subject): bool
    {
        return !$this->exclude($subject) && $this->include($subject);
    }

    /**
     * 
     * @return bool
     */
    private function include(string $subject): bool
    {
        $include = false;
        foreach ($this->includes as $check) {
            if ($check($subject)) {
                $include = true;
                break;
            }
        }
        return $include;
    }
    
    /**
     * 
     * @return bool
     */
    private function exclude(string $subject): bool
    {
        $exclude = false;
        foreach ($this->excludes as $check) {
            if ($check($subject)) {
                $exclude = true;
                break;
            }
        }
        return $exclude;        
    }

    /**
     * @param string $spec
     * @return callable
     */
    private function rule(string $spec): callable
    {
        $pattern = rtrim(ltrim($spec), '/ ');
        if (substr($pattern, 0, 3) == '**/') {
            // Starts with **/
            $tail = preg_quote(substr($pattern, 3), '|');
            $rule = $this->regex("|^(.+/)?{$tail}$|u");
        } else if (substr($pattern, -3) == '/**') {
            // Ends with /**
            $head = preg_quote(substr($pattern, 0, -3), '|');
            $rule = $this->regex("|^{$head}(/.+)?$|u");            
        } else if (strpos($pattern, '/**/') !== false) {
            // Contains /**/
            $parts = preg_split('|/\*\*/|', $pattern, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($parts as $i => $p) {
                $parts[$i] = preg_quote($p, '|');
            }
            $rule = $this->regex('|' . implode('/(.+/)?', $parts) . '|u');
        } else  if ($spec == '**') {
            // Equals **
            $rule = function () { return true; };
        } else {
            // fnmatch
            $rule = function ($subject) use ($pattern) {
                return fnmatch($pattern, $subject, FNM_PATHNAME);
            };
        }
        return $rule;
    }
    
    /**
     * 
     * @param string $pattern
     * @return Closure
     */
    private function regex(string $pattern)
    {
        return function ($subject) use ($pattern) {
            return (bool) preg_match($pattern, $subject);
        };
    }
    
}
