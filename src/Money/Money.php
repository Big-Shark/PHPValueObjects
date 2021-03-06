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

namespace SerendipityHQ\Component\ValueObjects\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exception\ParserException;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money as BaseMoney;
use Money\Parser\DecimalMoneyParser;
use SerendipityHQ\Component\ValueObjects\Common\ComplexValueObjectTrait;
use SerendipityHQ\Component\ValueObjects\Common\DisableWritingMethodsTrait;

/**
 * The class doesn't extend the base money object has it has private properties and methods that make difficult the
 * integration.
 *
 * So the base money object is stored in a proprty in this class and this class operates like a simple wrapper.
 *
 * {@inheritdoc}
 */
class Money implements MoneyInterface
{
    use ComplexValueObjectTrait {
        __construct as traitConstruct;
    }
    use DisableWritingMethodsTrait;

    /**
     * This represents the amount as Money intends it: in its base units.
     *
     * 10 = 00.1 {CURRENCY}
     * 100 = 1.00 {CURRENCY}
     *
     * @var int|string
     */
    private $baseAmount;

    /**
     * This represents the amount as a Human intends it: in its converted form.
     *
     * 00.1 {CURRENCY} = 10 units
     * 1.00 {CURRENCY} = 100 units
     *
     * @var float|int|string
     */
    private $humanAmount;

    /** @var Currency */
    private $currency;

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     * @throws ParserException
     */
    public function __construct(array $values = [])
    {
        // Set values in the object
        $this->traitConstruct($values);

        // Only one between baseAmount and humanAmount can be set
        if (null !== $this->baseAmount && null !== $this->humanAmount) {
            throw new \InvalidArgumentException('You can pass only one between "humanAmount" and "baseAmount". Both passed.');
        }

        // At least one between baseAmount and humanAmount MUST be set
        if (null === $this->baseAmount && null === $this->humanAmount) {
            throw new \InvalidArgumentException('You MUST pass one between "humanAmount" and "baseAmount". None passed.');
        }

        // If the base amount were given
        if (null !== $this->baseAmount) {
            $this->valueObject = new BaseMoney($this->baseAmount, $this->currency);
        }

        // If the human amount were given...
        if (null !== $this->humanAmount) {
            // Cast to string
            $this->humanAmount = (string) $this->humanAmount;

            // Replace "," with "."
            $this->humanAmount = str_replace(',', '.', $this->humanAmount);

            // Process it
            $currencies = new ISOCurrencies();

            $moneyParser = new DecimalMoneyParser($currencies);

            $this->valueObject = $moneyParser->parse($this->humanAmount, $this->currency);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedMethodCall
     */
    public function getBaseAmount(): string
    {
        return $this->valueObject->getAmount();
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedMethodCall
     */
    public function getCurrency(): Currency
    {
        return $this->valueObject->getCurrency();
    }

    /**
     * {@inheritdoc}
     */
    public function getHumanAmount(): string
    {
        return $this->__toString();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     * @throws ParserException
     * @psalm-suppress MixedMethodCall
     */
    public function add(MoneyInterface $other): MoneyInterface
    {
        $toAdd = new BaseMoney($other->getBaseAmount(), $other->getCurrency());

        $result = $this->valueObject->add($toAdd);

        return new static(['baseAmount' => $result->getAmount(), 'currency' => $this->currency]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     * @psalm-suppress MixedMethodCall
     *
     * @throws ParserException
     */
    public function subtract(MoneyInterface $other): MoneyInterface
    {
        $toAdd = new BaseMoney($other->getBaseAmount(), $other->getCurrency());

        $result = $this->valueObject->subtract($toAdd);

        return new static(['baseAmount' => $result->getAmount(), 'currency' => $this->currency]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     * @psalm-suppress MixedMethodCall
     *
     * @throws ParserException
     */
    public function divide($divisor, $roundingMode = BaseMoney::ROUND_HALF_UP): MoneyInterface
    {
        $result = $this->valueObject->divide($divisor, $roundingMode);

        return new static(['baseAmount' => $result->getAmount(), 'currency' => $this->currency]);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MixedMethodCall
     *
     * @throws \InvalidArgumentException
     * @throws ParserException
     */
    public function multiply($multiplier, $roundingMode = BaseMoney::ROUND_HALF_UP): MoneyInterface
    {
        $result = $this->valueObject->multiply($multiplier, $roundingMode);

        return new static(['baseAmount' => $result->getAmount(), 'currency' => $this->currency]);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(array $options = []): string
    {
        return $this->__toString();
    }

    /**
     * Sets the amount.
     *
     * @param int $baseAmount
     */
    protected function setBaseAmount(int $baseAmount): void
    {
        $this->baseAmount = (string) $baseAmount;
    }

    /**
     * @param float|int|string $amount
     */
    protected function setHumanAmount($amount): void
    {
        $this->humanAmount = $amount;
    }

    /**
     * Sets the currency.
     *
     * @param \Money\Currency|string $currency
     */
    protected function setCurrency($currency): void
    {
        if ( ! $currency instanceof Currency) {
            $currency = new Currency(strtoupper($currency));
        }

        $this->currency = $currency;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MixedArgument
     */
    public function __toString()
    {
        $currencies = new ISOCurrencies();
        $formatter  = new DecimalMoneyFormatter($currencies);

        return (string) $formatter->format($this->valueObject);
    }

    /**
     * {@inheritdoc}
     */
    public function __toArray(): array
    {
        return [
            'currency'    => $this->getCurrency()->getCode(),
            'baseAmount'  => $this->getBaseAmount(),
            'humanAmount' => $this->getHumanAmount(),
        ];
    }
}
