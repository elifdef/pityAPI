<?php

namespace API\Exception;

trait ErrorCode
{
    private array $errorCode = [
        # Server Error
        -3 => ['message' => 'Method `$m` class `$c` not implemented.', 'httpCode' => 501],
        -2 => ['message' => 'Class `$c` not implemented.', 'httpCode' => 501],
        -1 => ['message' => 'Internal Server Error.', 'httpCode' => 500],

        # Router Error
        1 => ['message' => 'Invalid URI.', 'httpCode' => 400],
        2 => ['message' => 'Invalid or not exist object.', 'httpCode' => 404],
        3 => ['message' => 'Invalid or not exist method for object.', 'httpCode' => 404],
        4 => ['message' => 'Invalid request method.', 'httpCode' => 405],
        5 => ['message' => 'Invalid JSON.', 'httpCode' => 405],
        6 => ['message' => 'Missing $[] params.', 'httpCode' => 405],
        7 => ['message' => 'Param `$p` can\'t be empty/null.', 'httpCode' => 405],

        # Auth Error
        10 => ['message' => 'Minimum length for username is ' . MIN_CHAR_USERNAME . ' symbols.', 'httpCode' => 406],
        11 => ['message' => 'Maximum length for username is ' . MAX_CHAR_USERNAME . ' symbols.', 'httpCode' => 414],
        12 => ['message' => 'Username contain invalid or rjaka symbol.', 'httpCode' => 406],
        13 => ['message' => 'Minimum length for password is ' . MIN_CHAR_PASSWORD . ' symbols.', 'httpCode' => 406],
        14 => ['message' => 'Password not equal.', 'httpCode' => 412],
        15 => ['message' => 'Invalid email.', 'httpCode' => 406],
        16 => ['message' => 'Other user have this username.', 'httpCode' => 409],
        17 => ['message' => 'Other user sign up with this email.', 'httpCode' => 409],
        18 => ['message' => 'You can\'t created more 1 account.', 'httpCode' => 404],

        19 => ['message' => 'Invalid email or password.', 'httpCode' => 404],
        20 => ['message' => 'Invalid token.', 'httpCode' => 404]
    ];
}