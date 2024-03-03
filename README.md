# Usage example

Given the following `Payload.json` file
```json
{
  "description": "test",
  "account": {
    "username": "loopcake",
    "email": "tangent.jotey@gmail.com",
    "created_at": 1212412351234,
    "modified_at": 1212412351235,
    "article": [
      {
        "title": "my article"
      }
    ]
  }
}
```
Run the following

```sh
php jtp.phar Payload.json 
```
To obtain a `Payload.php` file
```php
<?php
class Payload {
    /**
     * @var string $description
     * @var Account $account
     */
    public static function create(
        string $description,
        Account $account,
    ):self {
        return new self(
            description:$description,
            account:$account,
        );
    }

    /**
     * @var string $description
     * @var Account $account
     */
    private function __construct(
        public string $description,
        public Account $account,
    ){}
}

class Account {
    /**
     * @var string $username
     * @var string $email
     * @var int $createdAt
     * @var int $modifiedAt
     * @var array<AccountArticle> $article
     */
    public static function create(
        string $username,
        string $email,
        int $createdAt,
        int $modifiedAt,
        array $article
    ):self {
        return new self(
            username:$username,
            email:$email,
            createdAt:$createdAt,
            modifiedAt:$modifiedAt,
            article:$article,
        );
    }

    /**
     * @var string $username
     * @var string $email
     * @var int $createdAt
     * @var int $modifiedAt
     * @var array<AccountArticle> $article
     */
    private function __construct(
        public string $username,
        public string $email,
        public int $createdAt,
        public int $modifiedAt,
        public array $article
    ){}
}

class AccountArticle {
    /**
     * @var string $title
     */
    public static function create(
        string $title,
    ):self {
        return new self(
            title:$title,
        );
    }

    /**
     * @var string $title
     */
    private function __construct(
        public string $title,
    ){}
}
```

> [!NOTE]
> The resulting Php file is not formatted properly, you may want to run php-cs-fixer on it.