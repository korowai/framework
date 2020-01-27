<?php
/**
 * @file Testing/PackagesDetails.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

use Korowai\Testing\Traits\PackageDetailsMemberArrays;
use Korowai\Lib\Basic\Singleton;

/**
 * Describes expected details of all Korowai packages altogether.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PackagesDetails implements PackageDetailsInterface
{
    use Singleton;
    use PackageDetailsMemberArrays;

    protected $packageDetailsClasses = [
        Contracts\PackageDetails::class,
        Lib\Ldap\PackageDetails::class,
    ];

    protected $classesDetails = null;

    /**
     * {@inheritdoc}
     */
    public function classesDetails() : array
    {
        if ($this->classesDetails === null) {
            $arrays = array_map(function (string  $packageDetailClass) {
                return (new $packageDetailClass)->classesDetails();
            }, $this->packageDetailsClasses);
            $this->classesDetails = array_merge(...$arrays);
        }
        return $this->classesDetails;
    }

    /**
     * Returns an array of parent classes for *$class* extacted from packages' details.
     *
     * @param  string $class
     * @return array
     */
    public function getParentClasses(string $class) : array
    {
        return $this->getParentClassesRecursion($class);
    }

    protected function getParentClassesRecursion(string $class, array &$visited = null) : array
    {
        $classInheritanceMap = $this->classInheritanceMap();
        if (($parent = $classInheritanceMap[$class] ?? null) !== null && !($visited[$class] ?? false)) {
            $visited[$class] = true;
            return array_merge([$parent => $parent], $this->getParentClassesRecursion($parent, $visited));
        } else {
            return [];
        }
        return $parents;
    }
}

// vim: syntax=php sw=4 ts=4 et:
