<?php
namespace IvanDanilov\LaravelSocialiteGoodgame;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;
class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'GOODGAME';
    /**
     * @var string
     */
    protected $openId;
    /**
     * {@inheritdoc}.
     */
    protected $scopes = ['user.favorites'];
    /**
     * set Open Id.
     *
     * @param string $openId
     */
    public function setOpenId($openId)
    {
        $this->openId = $openId;
    }
    /**
     * {@inheritdoc}.
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://api2.goodgame.ru/oauth/authorize', $state);
    }
    /**
     * {@inheritdoc}.
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        $query = http_build_query($this->getCodeFields($state), '', '&', $this->encodingType);
        return $url.'?'.$query;
    }
    /**
     * {@inheritdoc}.
     */
    protected function getCodeFields($state = null)
    {
        return [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'scope' => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'state' => $state
        ];
    }
    /**
     * {@inheritdoc}.
     */
    protected function getTokenUrl()
    {
        return 'https://api2.goodgame.ru/oauth';
    }
    /**
     * {@inheritdoc}.
     */
    protected function getUserByToken($token)
    {
        // По другому GG ругается
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api2.goodgame.ru/info?access_token=' . urlencode($token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $user = json_decode($response, true);
        return $user['user'];
    }
    /**
     * {@inheritdoc}.
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['user_id'],
            'nickname' => $user['username'],
            'avatar'   => null,
            'name'     => null,
            'email'    => null,
        ]);
    }
    /**
     * {@inheritdoc}.
     */
    protected function getTokenFields($code)
    {
        return [
            'redirect_uri' => $this->redirectUrl,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];
    }
    /**
     * {@inheritdoc}.
     */
    public function getAccessTokenResponse($code)
    {

        $response = $this->getHttpClient()->request('POST', $this->getTokenUrl(), [
          'form_params' => $this->getTokenFields($code)
        ]);

        $this->credentialsResponseBody = json_decode($response->getBody(), true);
        $this->openId = $this->credentialsResponseBody['access_token'];
        return $this->credentialsResponseBody;
    }
}
