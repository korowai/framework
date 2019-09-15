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
 * A helper object used by the IndexMap::arrayCombine() method.
 */
class IndexMapArrayCombineAlgorithm
{
    protected $im;
    protected $i;
    protected $j;
    protected $ns; // new shrink (introduced by $new)
    protected $ts; // total shrink (accumulation of $old and $new)
    protected $old;
    protected $new;

    protected function reset(array $old, array $new)
    {
        $this->im = [];
        $this->i = 0;
        $this->j = 0;
        $this->ns =0 ;
        $this->ts = 0;
        $this->old = $old;
        $this->new = $new;
    }

    protected function finished()
    {
        return $this->i >= count($this->old) && $this->j >= count($this->new);
    }

    protected function isBefore()
    {
        if ($this->j >= count($this->new)) {
            return false;
        }
        if ($this->i >= count($this->old)) {
            return true;
        }
        return $this->new[$this->j][1] < ($this->old[$this->i][0] - $this->ns);
    }

    protected function isNotAfter()
    {
        if ($this->j >= count($this->new) || $this->i >= count($this->old)) {
            return false;
        }

        return $this->new[$this->j][0] <= ($this->old[$this->i][0] - $this->ns);
    }

    protected function stepBefore()
    {
        $this->ts += ($this->new[$this->j][1] - $this->new[$this->j][0]);
        $this->im[] = [$this->new[$this->j][0], $this->new[$this->j][0] + $this->ts];
        $this->ns += ($this->new[$this->j][1] - $this->new[$this->j][0]);
        $this->j++;
    }

    protected function stepEnclosing()
    {
        $this->ts += ($this->new[$this->j][1] - $this->new[$this->j][0]);
        do {
            $this->ts += ($this->old[$this->i][1] - $this->old[$this->i][0]);
            $this->i++;
        } while ($this->i < count($this->old) && ($this->old[$this->i][0] - $this->ns) <= $this->new[$this->j][1]);
        $this->im[] = [$this->new[$this->j][0], $this->new[$this->j][0] + $this->ts];
        $this->ns += ($this->new[$this->j][1] - $this->new[$this->j][0]);
        $this->j++;
    }

    protected function stepAfter()
    {
        if ($this->i < count($this->old)) {
            $this->im[] = [$this->old[$this->i][0] - $this->ns, $this->old[$this->i][1]];
            $this->ts += ($this->old[$this->i][1] - $this->old[$this->i][0]);
            $this->i++;
        } else {
            throw \RuntimeException("internal error");
        }
    }

    public function __invoke(array $old, array $new)
    {
        $this->reset($old, $new);

        while (!$this->finished()) {
            if ($this->isBefore()) {
                //
                // $new[$this->j] on the left side of $old[$this->i]
                //
                $this->stepBefore();
            } elseif ($this->isNotAfter()) {
                //
                // $new[$this->j] encloses $old[$this->i] (and perhaps $old[$this->i+1], ...)
                //
                $this->stepEnclosing();
            } else {
                //
                // $new[$this->j] on the right side of $old[$this->i]
                //
                $this->stepAfter();
            }
        }
        return $this->im;
    }
}

// vim: syntax=php sw=4 ts=4 et:
