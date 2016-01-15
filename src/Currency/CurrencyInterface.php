<?PHP

/**
 *  A Phone value object.
 *
 *
 *  @author      Adamo Crespi <hello@aerendir.me>
 *  @copyright   Copyright (c) 2015, Adamo Crespi
 *  @license     MIT License
 */
namespace SerendipityHQ\Component\ValueObjects\Currency;

use SerendipityHQ\Component\ValueObjects\Common\ValueObjectInterface;

interface CurrencyInterface extends ValueObjectInterface
{
    public function getCurrencyCode();
}
