<?php
namespace SocialiteProviders\Weixin;
use SocialiteProviders\Manager\SocialiteWasCalled;
class GoodgameExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            'goodgame', __NAMESPACE__.'\Provider'
        );
    }
}
