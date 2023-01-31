<?php

declare(strict_types=1);

namespace Unleashed\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;

final class ForbiddenSingleLineCommentsSniff implements Sniff
{
    public const CODE_COMMENT_FORBIDDEN = 'CommentForbidden';

    /** @var string[] */
    public array $forbiddenCommentPatterns = [];

    /**
     * @return array<int, (int|string)>
     */
    public function register(): array
    {
        return [
            T_COMMENT,
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

        $content = $tokens[$stackPtr]['content'];

        foreach (SniffSettingsHelper::normalizeArray($this->forbiddenCommentPatterns) as $forbiddenCommentPattern) {
            if (! SniffSettingsHelper::isValidRegularExpression($forbiddenCommentPattern)) {
                throw new \Exception(\sprintf('%s is not valid PCRE pattern.', $forbiddenCommentPattern));
            }

            if (\preg_match($forbiddenCommentPattern, $content) === 0) {
                continue;
            }

            $fix = $phpcsFile->addFixableError(
                \sprintf('Code contains forbidden comment "%s".', \trim($content)),
                $stackPtr,
                self::CODE_COMMENT_FORBIDDEN
            );

            if (! $fix) {
                continue;
            }

            $phpcsFile->fixer->beginChangeset();
            $fixedContent = \preg_replace($forbiddenCommentPattern, '', $content);
            $phpcsFile->fixer->replaceToken($stackPtr, $fixedContent);
            $phpcsFile->fixer->endChangeset();
        }
    }
}
