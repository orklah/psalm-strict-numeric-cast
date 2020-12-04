<?php
namespace Orklah\PsalmStrictNumericCast;

use Orklah\PsalmStrictNumericCast\Hooks\StrictNumericCastAnalyzer;
use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

class Plugin implements PluginEntryPointInterface
{
    /** @return void */
    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null): void
    {
        if(class_exists(StrictNumericCastAnalyzer::class)){
            $psalm->registerHooksFromClass(StrictNumericCastAnalyzer::class);
        }
    }
}
