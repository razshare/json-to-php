<?php
class Payload {
    /**
     * @var string $username
     * @var array<Article> $article
     */
    private function __construct(
        public string $username,
        public array $article,
    ){}
}

class Article {
    /**
     * @var string $title
     * @var string $description
     */
    private function __construct(
        public string $title,
        public string $description,
    ){}
}

