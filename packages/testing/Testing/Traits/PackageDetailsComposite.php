<?php
/**
 * @file Testing/Traits/PackageDetailsComposite.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Traits;

use Korowai\Testing\PackageDetailsInterface;

/**
 * Facilitates implementation of
 * [PackageDetailsInterface](\.\./PackageDetailsInterface.html)
 * for meta-packages, such as frameworks (collecting multiple packages). The
 * trait collects details from multiple instances of
 * [PackageDetailsInterface](\.\./PackageDetailsInterface.html).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PackageDetailsComposite
{
    /**
     * Returns a key ``=>`` value array with package names as keys and objects
     * implementing ``PackageDetailsInterface`` as values.
     *
     * @return array
     */
    abstract public function packagesDetails() : array;

    /**
     * @var array
     */
    protected $classesDetails;

    /**
     * Returns an array of *classesDetails* collected from all packages
     * returned by *$this->packagesDetails()*.
     *
     * @return array
     */
    public function classesDetails() : array
    {
        if (!isset($this->classesDetails)) {
            $this->classesDetails = $this->collectFromPackages('classesDetails');
        }
        return $this->classesDetails;
    }

    /**
     * Returns merged results of application of *$getter* to all instances of
     * [PackageDetailsInterface](\.\./PackageDetailsInterface.html) returned by
     * *$this->packagesDetails()*.
     *
     * @param  mixed $getter
     * @return array
     */
    protected function collectFromPackages($getter) : array
    {
        $array = [];
        if (is_string($getter)) {
            $getter = function ($package) use ($getter) {
                return call_user_func([$package, $getter]);
            };
        }
        foreach ($this->packagesDetails() as $key => $package) {
            $array = array_merge($array, call_user_func($getter, $package));
        }
        return $array;
    }
}

// vim: syntax=php sw=4 ts=4 et:
