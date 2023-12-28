<?php

namespace Abedin\Boiler\Lib\Code; 

class RepositoryCode 
{
public static function writeGetAccessTokenMethod(): string
{
    return "\n\n    public static function getAccessToken(User \$user)
    {
        \$token = \$user->createToken('user token');

        return [
            'auth_type' => 'Bearer',
            'token' => \$token->accessToken,
            'expires_at' => \$token->token->expires_at->format('Y-m-d H:i:s'),
        ];
    }";
}
}