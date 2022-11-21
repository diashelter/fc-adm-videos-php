<?php
declare(strict_types=1);

use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;

it('test validate not null messagem default', function () {
    expect(function () {
        $value = '';
        DomainValidation::notNull($value);
    })->toThrow(EntityValidationException::class, 'Should not be empty');
});

it('test validate not null message customized', function () {
    expect(function () {
        $value = '';
        DomainValidation::notNull($value, 'Value not empty');
    })->toThrow(EntityValidationException::class, 'Value not empty');
});

it('test validate max length string message customized', function () {
    expect(function () {
        $value = '1234';
        DomainValidation::strMaxLength($value, 3, 'Custom messagem');
    })->toThrow(EntityValidationException::class, 'Custom messagem');
});

it('test validate min length string message customized', function () {
    expect(function () {
        $value = '1234';
        DomainValidation::strMinLength($value, 5, 'Custom messagem');
    })->toThrow(EntityValidationException::class, 'Custom messagem');
});

it('test validate string empty and max length message customized', function () {
    expect(function () {
        $value = '12345';
        DomainValidation::strCanNullAndMaxLength($value, 3, 'Custom messagem');
    })->toThrow(EntityValidationException::class, 'Custom messagem');
});
