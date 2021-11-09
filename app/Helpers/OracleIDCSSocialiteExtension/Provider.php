<?php

namespace App\Helpers\OracleIDCSSocialiteExtension;

use \Illuminate\Support\Arr;
use \SocialiteProviders\Manager\OAuth2\AbstractProvider;
use \SocialiteProviders\Manager\OAuth2\User;
use Laravel\Socialite\Two\InvalidStateException;

class Provider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'ORACLE-IDCS';

    /**
     * Scopes defintions.
     *
     * @see https://developer.okta.com/docs/reference/api/oidc/#scopes
     */
    public const SCOPE_OPENID = 'openid';
    public const SCOPE_PROFILE = 'profile';
    public const SCOPE_EMAIL = 'email';
    public const SCOPE_ADDRESS = 'address';
    public const SCOPE_PHONE = 'phone';
    public const SCOPE_OFFLINE_ACCESS = 'offline_access';
    public const SCOPE_APP_ROLES = 'approles';
    public const SCOPE_GROUPS = 'groups';
    public const SCOPE_OTHER = 'urn:opc:idm:t.user.me';

    /**
     * {@inheritdoc}
     */
    protected $scopes = [
        self::SCOPE_OPENID,
        self::SCOPE_PROFILE,
        self::SCOPE_EMAIL,
        self::SCOPE_APP_ROLES,
        self::SCOPE_GROUPS,
        self::SCOPE_OTHER,
    ];

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * Base user array
     * @var Array
     */
    protected $baseUserArray;

    /**
     * Get base user array
     */
    public function baseUser()
    {
        if ($this->baseUserArray) {
            return $this->baseUserArray;
        }

        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        $this->baseUserArray = $this->getUserByToken(
            $token = Arr::get($response, 'access_token')
        );
        
        return $this->baseUserArray;
    }

    /**
     * Get base user array and verify they are a part of app group
     */
    public function verifiedUser()
    {
        $this->baseUser();

        if ($this->getConfig('group_name') == null) {
            return $this->baseUserArray;
        }

        $verified = false;

        if ($this->baseUserArray['groups']) {
            foreach ($this->baseUserArray['groups'] as $group) {
                if ($group['name'] == $this->getConfig('group_name')) {
                    return $this->baseUserArray;
                }
            }
        }

        return false;
    }

    protected function getOracleIDCSUrl()
    {
        return $this->getConfig('base_url');
    }

    /**
     * {@inheritdoc}
     */
    public static function additionalConfigKeys()
    {
        return ['base_url', 'enterprise_key', 'group_name'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getOracleIDCSUrl().'/oauth2/v1/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->getOracleIDCSUrl().'/oauth2/v1/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getOracleIDCSUrl().'/oauth2/v1/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return array_merge(json_decode($response->getBody(), true), $this->getEnterpiseUserInfoByToken($token));
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnterpiseUserInfoByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getOracleIDCSUrl().'/admin/v1/Me', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return (json_decode($response->getBody(), true)[$this->getConfig('enterprise_key')] ?? []);
    }

    /**
     * {@inheritdoc}
     * 
     * IMPORTANT: not optimized
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'             => Arr::get($user, 'sub'),
            'email'          => Arr::get($user, 'email'),
            'email_verified' => Arr::get($user, 'email_verified', false),
            'nickname'       => Arr::get($user, 'nickname'),
            'name'           => Arr::get($user, 'name'),
            'first_name'     => Arr::get($user, 'given_name'),
            'last_name'      => Arr::get($user, 'family_name'),
            'profileUrl'     => Arr::get($user, 'profile'),
            'address'        => Arr::get($user, 'address'),
            'phone'          => Arr::get($user, 'phone'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
