<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Util;

/**
 * Implements the imOverIm
 */
class ImOverImAlgorithm
{
    protected $old;
    protected $new;

    /**
     * Initializes the object.
     */
    public function __construct(array $old, array $new)
    {
        $this->old = $old;
        $this->new = $new;
    }

    /**
     * Runs the algorithm and returns the result.
     */
    public function __invoke() : array
    {
        $im = [];
        $ns = 0; // new shrink (introduced by $new)
        $ts = 0; // total shrink (cumulation of $old and $new)
        for($i=0, $j=0; $i < count($this->old) || $j < count($this->new); ) {
            if($this->isOnTheLeft($i, $j, $ns)) {
                //
                // $this->new[$j] on the left side of $this->old[$i]
                //
                $ts += ($this->new[$j][1] - $this->new[$j][0]);
                $im[] = [$this->new[$j][0], $this->new[$j][0] + $ts];
                $ns += ($this->new[$j][1] - $this->new[$j][0]);
                $j++;
            } elseif($this->isEnclosing($i, $j, $ns)) {
                //
                // $this->new[$j] encloses $this->old[$i] (and perhaps $this->old[$i+1], ...)
                //
                $ts += ($this->new[$j][1] - $this->new[$j][0]);
                do {
                    $ts += ($this->old[$i][1] - $this->old[$i][0]);
                    $i++;
                } while($i < count($this->old) && $this->new[$j][1] >= $this->old[$i][0] - $ns);
                $im[] = [$this->new[$j][0], $this->new[$j][0] + $ts];
                $ns += ($this->new[$j][1] - $this->new[$j][0]);
                $j++;
            } elseif($i < count($this->old)) {
                //
                // $this->new[$j] on the right side of $this->old[$i]
                //
                $im[] = [$this->old[$i][0] - $ns, $this->old[$i][1]];
                $ts += ($this->old[$i][1] - $this->old[$i][0]);
                $i++;
            } else {
                throw \RuntimeException("internal error");
            }
        }
        return $im;
    }

    /**
     * Returns true if $this->new[$j] is on the left side of $this->old[$i]
     */
    protected function isOnTheLeft(int $i, int $j, int $ns) : bool
    {
        if($j >= count($this->new)) {
            return false;
        }
        if($i >= count($this->old)) {
            return true;
        }
        return $this->new[$j][1] < ($this->old[$i][0] - $ns);
    }

    /**
     * Returns true if $this->new[$j] encloses $this->old[$i] (and perhaps $this->old[$i+1], ...)
     */
    protected function isEnclosing(int $i, int $j, int $ns)
    {
        if($j >= count($this->new) || $i >= count($this->old)) {
            return false;
        }
        return $this->new[$j][0] <= $this->old[$i][0] - $ns;
    }

}

// vim: syntax=php sw=4 ts=4 et:
