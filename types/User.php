<?php
class User {
    /**
     * @param string $name
     * @param array<Article> $article
     */
    private function __construct(
        public string $name,
        public array $article,
    ){}
}

class CommentAuthor {
    /**
     * @param string $name
     */
    private function __construct(
        public string $name,
    ){}
}

class ArticleComment {
    /**
     * @param CommentAuthor $author
     * @param int $createdAt
     * @param string $content
     */
    private function __construct(
        public CommentAuthor $author,
        public int $createdAt,
        public string $content,
    ){}
}

class Article {
    /**
     * @param string $title
     * @param int $createdAt
     * @param string $content
     * @param array<array<ArticleComment>> $comment
     */
    private function __construct(
        public string $title,
        public int $createdAt,
        public string $content,
        public array $comment,
    ){}
}

