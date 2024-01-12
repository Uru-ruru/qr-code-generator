# QR code Generator Example

This example uses the following list of packages as a basis:

1. symfony - https://symfony.com/
2. endroid/qr-code-bundle - https://github.com/endroid/qr-code-bundle
3. endroid/qr-code - https://github.com/endroid/qr-code

# Use

1. You can use it with sympony console by starting server: ` symfony server:start`
2. Go to:  http://127.0.0.1:8000  and try to create a new code.

# Implementation Features

1. All params have `base64_encode()` to file name: `/qr_code/eyJkYXRhIjoiMjEzMjEzMjEzIiwic2l6ZSI6IjMwMCJ9.png`
2. Using caching for repeating qr codes: 
```php
    return (new QrCodeResponse($result))
            ->setCache([
                'must_revalidate' => false,
                'no_cache' => false,
                'no_store' => false,
                'no_transform' => false,
                'public' => true,
                'private' => false,
                'proxy_revalidate' => false,
                'max_age' => 600,
                's_maxage' => 600,
                'immutable' => true,
                'last_modified' => new \DateTime(),
                'etag' => $data
            ]);
```
