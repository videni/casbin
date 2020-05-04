<?php

namespace Videni\Bundle\CasbinBundle\Util;

use Doctrine\Common\Util\ClassUtils as DoctrineClassUtils;

/**
 * Class related functionality for objects that
 * might or might not be proxy objects at the moment.
 *
 * @see DoctrineClassUtils
 */
final class ClassUtils
{
    /**
     * Marker for Proxy class names.
     *
     * @var string
     */
    const MARKER = '__CG__';

    /**
     * Length of the proxy marker.
     *
     * @var int
     */
    const MARKER_LENGTH = 6;

    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Gets the real class name of a class name that could be a proxy.
     *
     * @param string|object $object
     *
     * @return string
     */
    public static function getRealClass($object)
    {
        $class = is_object($object) ? get_class($object) : $object;

        if (class_exists('Doctrine\Common\Util\ClassUtils')) {
            return DoctrineClassUtils::getRealClass($class);
        }

        // fallback in case doctrine common  is not installed
        if (false === $pos = strrpos($class, '\\'.self::MARKER.'\\')) {
            return $class;
        }

        return substr($class, $pos + self::MARKER_LENGTH + 2);
    }
}
