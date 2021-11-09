<?php

namespace App\Helpers\OracleIDCSSocialiteExtension;

use \SocialiteProviders\Manager\SocialiteWasCalled;

class OracleIDCSExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('oracle-idcs', Provider::class);
    }
}
