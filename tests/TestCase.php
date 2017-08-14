<?php
/**
 * Copyright 2017 Alexandru Guzinschi <alex@gentle.ro>
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */
namespace Vimishor\Cnp\Test;

use PHPUnit_Framework_TestCase;

/**
 * @author Alexandru Guzinschi <alex@gentle.ro>
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    private static $cloneCache = [];

    public function setUp()
    {
        $this->autoCheckCloning(
            str_replace(array('Test\\', 'Test'), '', get_class($this))
        );
    }

    /**
     * Make sure the `__clone` method is not part of the public API.
     *
     * @param string $class
     */
    protected function assertObjectCantBeCloned($class)
    {
        $ref = new \ReflectionClass($class);
        if (false !== $ref->isCloneable()) {
            $this->fail(sprintf('"%s" should not be cloneable.', $class));
        }
    }

    /**
     * Automatically check each class if is cloneable.
     *
     * This is used as a method to enforce no cloning rule for any
     * future objects.
     *
     * @param string $class
     */
    private function autoCheckCloning($class)
    {
        if (!in_array($class, self::$cloneCache, false)) {
            self::$cloneCache[] = $class;
            $this->assertObjectCantBeCloned($class);
        }
    }
}
