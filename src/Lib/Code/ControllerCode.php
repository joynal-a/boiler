<?php

namespace Abedin\Boiler\Lib\Code;

use Abedin\Boiler\Lib\Manager\ReadFile;

class ControllerCode {
public static function webAuth(bool $isCreteUserRepo)
{
    $findQuery = $isCreteUserRepo ? 'UserRepository::query()->where("email", $loginRequest->email)->first();' : 'User::where("email", $loginRequest->email)->first();';

    $code = "\n    public function index()
    {
        return view('your.blade.php');
    }";

    $code .= "\n\n    public function signin(Request \$loginRequest)
    {
        \$user = \$this->isAuthenticate(\$loginRequest);
        \$loginRequest->only('email', 'password');
        
        if (!\$user) {
            return redirect()->back()
                ->withErrors(['email' => ['Invalid credentials']])
                ->withInput();
        }
            
        Auth::login(\$user);
        return to_route('root');
    }";

    $code .= "\n\n    public function signout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }";

    $code .= "\n\n    private function isAuthenticate(\$loginRequest)
    {
        \$user = $findQuery
        if (!is_null(\$user) && \$user->is_active && Hash::check(\$loginRequest->password, \$user->password)) {
            return \$user;
        }
        return false;
    }";

    return $code;
}

    public static function apiAuth(bool $isCreteUserRepo, $whichOne = 'passport')
    {
        $accessToken = "\$this->getAccessToken(\$user)";
        $query = "User::";
        $accessCode = RepositoryCode::writeGetAccessTokenMethod();

        if($isCreteUserRepo){
            if($whichOne == 'sanctum'){
                $data = [
                    'old' =>["\$token = \$user->createToken('user token');", "'token' => \$token->accessToken,"],
                    'new' => ["        \$token = \$user->createToken('user token')->plainTextToken;\n","            'token' => \$token,\n"]
                ];
            }else{
                $data = [
                    'old' => ["\$token = \$user->createToken('user token')->plainTextToken;","'token' => \$token,"], 
                    'new' => ["        \$token = \$user->createToken('user token');\n", "            'token' => \$token->accessToken,\n"]
                ];
            }
            $accessToken = "UserRepository::getAccessToken(\$user)";
            $query = "UserRepository::query()->";
            $accessCode = null;

            $userRepositoryPath = app_path('Repositories/UserRepository.php');
            ReadFile::ignoreOrWrite($userRepositoryPath, ['getAccessToken('], RepositoryCode::writeGetAccessTokenMethod(), $data);
        }

        $code = self::writeSigninMethod($accessToken);
        $code .= self::writeSingoutMethod();
        $code .= self::writeAuthenticateMethod($query);
        $code .= $accessCode;

        return $code;
    }

private static function writeSigninMethod($accessToken): string
{
    return "\n    public function signin(SigninRequest \$request)
    {
        \$user = \$this->authenticate(\$request);
        if (\$user) {
            return \$this->json('Log In Successfull', [
                'user' => UserResource::make(\$user),
                'access' => $accessToken
            ]);
        }

        return \$this->json('Credential is invalid!', [], Response::HTTP_BAD_REQUEST);
    }";
}

private static function writeSingoutMethod(): string
{
    return "\n\n    public function singout()
    {
        \$user = auth()->user();
        if (\$user) {
            \$user->token()->revoke();

            return \$this->json('Logged out successfully!');
        }
    }";
}

private static function writeAuthenticateMethod($query): string
{
    return "\n\n    private function authenticate(SigninRequest \$request)
    {
        \$user = {$query}where('email', \$request->email)->first();

        if (!is_null(\$user) && Hash::check(\$request->password, \$user->password)) {
            return \$user;
        }

        return null;
    }";
}

public static function writeJsonMethod(): string
{
    return "\n    protected function json(string \$message = null, \$data = [], \$statusCode = 200, array \$headers = [], \$options = 0)
    {
        \$content = [];
        if (\$message) {
            \$content['message'] = \$message;
        }

        if (! empty(\$data)) {
            \$content['data'] = \$data;
        }

        return response()->json(\$content, \$statusCode, \$headers, \$options);
    }";
}

}