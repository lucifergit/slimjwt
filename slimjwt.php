<?php

class Slimjwt
{
    //$payload encode
    public static function encode($payload,$key,$alg = 'HS256',$keyId = null,$head = null)
    {
        //$key = self::urlsafeB64Encode($key);
        $header = array('typ' => 'JWT', 'alg' => $alg);
        if ($keyId !== null) {
            $header['kid'] = $keyId;
        }
        if ( isset($head) && is_array($head) ) {
            $header = array_merge($head, $header);
        }
        $jwt = self::urlsafeB64Encode(json_encode($header)) . '.' . self::urlsafeB64Encode(json_encode($payload));
        return $jwt . '.' . self::signature($jwt, $key, $alg);
    }

    //sign
    public static function signature($input,$key,$method = 'HS256')
    {
        $methods = array(
        'HS256' => 'sha256',
        'HS384' => 'sha384',
        'HS512' => 'sha512',
    );
        if (empty($methods[$method])) {
        throw new DomainException('Algorithm not supported');
    }
        return self::urlsafeB64Encode(hash_hmac($methods[$method], $input, $key,true));
    }

    //decode
    public static function decode($jwt,$key)
    {
        $tokens = explode('.', $jwt);
        //$key    = self::urlsafeB64Encode($key);
        if (count($tokens)!=3)
            return false;

        list($header64, $payload64, $sign) = $tokens;

        $header = json_decode(self::urlsafeB64Decode($header64), true);
        if (empty($header['alg']))
            return false;

        if (self::signature($header64 . '.' . $payload64, $key, $header['alg']) !== $sign)
            return false;
        $payload = json_decode(self::urlsafeB64Decode($payload64), true);

        $time = $_SERVER['REQUEST_TIME'];
        if (isset($payload['iat']) && $payload['iat'] > $time)
            return false;
        if (isset($payload['nbf']) && $payload['nbf'] > $time)
            return false;
        if (isset($payload['exp']) && $payload['exp'] < $time)
            return false;
        return $payload;
    }
    
    //urlsafeB64Encode
    public static function urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    //urlsafeB64Decode
    public function urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

}
