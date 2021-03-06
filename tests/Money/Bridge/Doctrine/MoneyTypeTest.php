<?php

/*
 * This file is part of PHP Value Objects.
 *
 * Copyright Adamo Aerendir Crespi 2015-2017.
 *
 * @author    Adamo Aerendir Crespi <hello@aerendir.me>
 * @copyright Copyright (C) 2015 - 2020 Aerendir. All rights reserved.
 * @license   MIT
 */

namespace SerendipityHQ\Component\ValueObjects\Tests\Money\Bridge\Doctrine;

use PHPUnit\Framework\TestCase;

/**
 * @author Adamo Crespi <hello@aerendir.me>
 */
class MoneyTypeTest extends TestCase
{
    /**
     * @doesNotPerformAssertion
     */
    public function testMoneyType()
    {
        $this::markTestSkipped('See http://stackoverflow.com/questions/39900136/how-to-test-doctrine-custom-types');
    }
}
