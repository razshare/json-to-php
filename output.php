<?php
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

