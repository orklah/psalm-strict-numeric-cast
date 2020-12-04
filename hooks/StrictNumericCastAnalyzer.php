<?php

declare(strict_types=1);

namespace Orklah\PsalmStrictNumericCast\Hooks;

use Orklah\PsalmInsaneComparison\Hooks\InsaneComparison;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\Cast\Double;
use Psalm\Codebase;
use Psalm\CodeLocation;
use Psalm\Context;
use Psalm\Issue\PluginIssue;
use Psalm\IssueBuffer;
use Psalm\Plugin\Hook\AfterExpressionAnalysisInterface;
use Psalm\StatementsSource;
use Psalm\Type;
use Psalm\Type\Atomic\TLiteralString;

class StrictNumericCastAnalyzer implements AfterExpressionAnalysisInterface
{
    public static function afterExpressionAnalysis(
        Expr $expr,
        Context $context,
        StatementsSource $statements_source,
        Codebase $codebase,
        array &$file_replacements = []
    ): ?bool
    {
        if(!$expr instanceof Int_ && !$expr instanceof Double){
            return true;
        }

        $previous_type = $statements_source->getNodeTypeProvider()->getType($expr->expr);

        if($previous_type instanceof Type\Atomic\TNumericString) {
            //everything is good!
            return true;
        } elseif (
            $previous_type instanceof TLiteralString &&
            preg_match('#\d#', $previous_type->value[0] ?? '') // will probably have to inverse the check to forbid chars instead
        ) {
            //this is good too. It's not a numeric-string but this is actually more precise
            return true;
        } elseif ($previous_type instanceof Type\Atomic\TString) {
            //this is what we're looking for. A string that is not numeric nor a literal numeric
            return true;
        } else {
            //nothing to see here
            return true;
        }

        if (IssueBuffer::accepts(
            new StrictNumericCast(
                'Unsafe cast from numeric to string. Consider documenting the string as numeric-string.',
                new CodeLocation($statements_source, $expr)
            ),
            $statements_source->getSuppressedIssues()
        )
        ) {
            // continue
        }

        return true;
    }
}

class StrictNumericCast extends PluginIssue
{
}
