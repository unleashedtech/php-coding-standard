<?php

declare(strict_types=1);

namespace Unleashed\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

final class InheritDocFormatSniff implements Sniff
{
    public const CODE_INVALID_INHERITDOC_STYLE = 'InvalidInheritDocStyle';

    /**
     * The required style
     *
     * @var string
     */
    public $style = '{@inheritDoc}';

    /**
     * @return array<int, (int|string)>
     */
    public function register(): array
    {
        return [
            T_DOC_COMMENT_OPEN_TAG,
        ];
    }

    /**
     * @param int $stackPtr
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $stackPtr + 1; $i < $tokens[$stackPtr]['comment_closer']; $i++) {
            if (\in_array($tokens[$i]['code'], [T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR], true)) {
                continue;
            }

            $content = $tokens[$i]['content'];

            if (\preg_match('~^(?:{@inheritDoc}|@inheritDoc)$~i', $content) === 0) {
                continue;
            }

            $fixed = \preg_replace('~({@inheritDoc}|@inheritDoc)~i', $this->style, $content);
            if ($content === $fixed) {
                continue;
            }

            $fix = $phpcsFile->addFixableError(
                \sprintf('Incorrect formatting of "%s"', $this->style),
                $i,
                self::CODE_INVALID_INHERITDOC_STYLE
            );

            if (! $fix) {
                return;
            }

            $phpcsFile->fixer->beginChangeset();
            $phpcsFile->fixer->replaceToken($i, $fixed);
            $phpcsFile->fixer->endChangeset();
        }
    }
}
