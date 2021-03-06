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

namespace SerendipityHQ\Component\ValueObjects\Address\Bridge\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use SerendipityHQ\Component\ValueObjects\Address\Address;

/**
 * {@inheritdoc}
 *
 * @ORM\Embeddable
 */
class AddressEmbeddable extends Address
{
    /**
     * Returns the full array, with null values, too.
     * This array is then used in the Form type.
     *
     * @return array<string,string|null>
     */
    public function _toArray(): array
    {
        return [
            'street'             => $this->getStreet(),
            'extraLine'          => $this->getExtraLine(),
            'postalCode'         => $this->getPostalCode(),
            'locality'           => $this->getLocality(),
            'dependentLocality'  => $this->getDependentLocality(),
            'administrativeArea' => $this->getAdministrativeArea(),
            'countryCode'        => $this->getCountryCode(),
        ];
    }

    /**
     * @param string|null $administrativeArea
     */
    protected function setAdministrativeArea(?string $administrativeArea): void
    {
        if (null !== $administrativeArea) {
            parent::setAdministrativeArea($administrativeArea);
        }
    }

    /**
     * @param string|null $countryCode
     */
    protected function setCountryCode(?string $countryCode): void
    {
        if (null !== $countryCode) {
            parent::setCountryCode($countryCode);
        }
    }

    /**
     * @param string|null $dependentLocality
     */
    protected function setDependentLocality(?string $dependentLocality): void
    {
        if (null !== $dependentLocality) {
            parent::setDependentLocality($dependentLocality);
        }
    }

    /**
     * @param string|null $street
     */
    protected function setStreet(?string $street): void
    {
        if (null !== $street) {
            parent::setStreet($street);
        }
    }

    /**
     * @param string|null $extraLine
     */
    protected function setExtraLine(?string $extraLine): void
    {
        if (null !== $extraLine) {
            parent::setExtraLine($extraLine);
        }
    }

    /**
     * @param string|null $locality
     */
    protected function setLocality(?string $locality): void
    {
        if (null !== $locality) {
            parent::setLocality($locality);
        }
    }

    /**
     * @param string|null $postalCode
     */
    protected function setPostalCode(?string $postalCode): void
    {
        if (null !== $postalCode) {
            parent::setPostalCode($postalCode);
        }
    }
}
