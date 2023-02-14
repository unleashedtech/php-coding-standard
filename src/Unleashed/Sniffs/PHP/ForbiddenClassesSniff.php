<?php

declare(strict_types=1);

namespace Unleashed\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\ReferencedNameHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;

final class ForbiddenClassesSniff implements Sniff
{
    public const FORBIDDEN = 'Forbidden';

    /**
     * A list of fully-qualified class, interface, or trait names
     *
     * @var string[]
     */
    public array $forbiddenClasses = [
        // phpcs:disable Unleashed.PHP.ForbiddenClasses.Forbidden
        \DateTime::class,
        // phpcs:enable
    ];

    /**
     * If true, an error will be thrown; otherwise a warning
     */
    public bool $error = true;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * {@inheritDoc}
     */
    public function register(): array
    {
        return [\T_OPEN_TAG];
    }

    /**
     * {@inheritDoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (TokenHelper::findPrevious($phpcsFile, T_OPEN_TAG, $stackPtr - 1) !== null) {
            return;
        }

        $referencedNames = ReferencedNameHelper::getAllReferencedNames($phpcsFile, $stackPtr);

        foreach ($referencedNames as $referencedName) {
            $pointer = $referencedName->getStartPointer();
            $name = $referencedName->getNameAsReferencedInFile();

            $fullyQualifiedName = NamespaceHelper::resolveClassName($phpcsFile, $name, $pointer);

            if (! \in_array($fullyQualifiedName, $this->forbiddenClasses, true)) {
                continue;
            }

            $error = \sprintf('The use of "%s" is forbidden', $fullyQualifiedName);
            if ($this->error) {
                $phpcsFile->addError($error, $pointer, self::FORBIDDEN);
            } else {
                $phpcsFile->addWarning($error, $pointer, self::FORBIDDEN);
            }
        }
    }
}
