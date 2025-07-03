<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class SqlInjectionSafeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var SqlInjectionSafe $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $dangerous = [  'select', 'insert', 'delete', 'update', 'drop', '--', ';', '/*', '*/', '@@',
                        'char', 'nchar', 'varchar', 'nvarchar', 'alter', 'begin', 'cast', 'create',
                        'cursor', 'declare', 'exec', 'fetch', 'kill', 'open', 'sys', 'sysobjects', 'syscolumns'];

        foreach ($dangerous as $keyword) {
            if (stripos($value, $keyword) !== false) { //case-insensitive operation
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
                return;
            }
        }
    }
}
