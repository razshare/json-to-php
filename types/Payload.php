<?php
class Payload {
    /**
     * @var string $url
     * @var string $sha
     * @var string $nodeId
     * @var string $htmlUrl
     * @var string $commentsUrl
     * @var Commit $commit
     * @var Author $author
     * @var Committer $committer
     * @var array<Parents> $parents
     */
    private function __construct(
        public string $url,
        public string $sha,
        public string $nodeId,
        public string $htmlUrl,
        public string $commentsUrl,
        public Commit $commit,
        public Author $author,
        public Committer $committer,
        public array $parents,
    ){}
}

class Commit {
    /**
     * @var string $url
     * @var CommitAuthor $author
     * @var CommitCommitter $committer
     * @var string $message
     * @var CommitTree $tree
     * @var int $commentCount
     * @var CommitVerification $verification
     */
    private function __construct(
        public string $url,
        public CommitAuthor $author,
        public CommitCommitter $committer,
        public string $message,
        public CommitTree $tree,
        public int $commentCount,
        public CommitVerification $verification,
    ){}
}

class Author {
    /**
     * @var string $login
     * @var int $id
     * @var string $nodeId
     * @var string $avatarUrl
     * @var string $gravatarId
     * @var string $url
     * @var string $htmlUrl
     * @var string $followersUrl
     * @var string $followingUrl
     * @var string $gistsUrl
     * @var string $starredUrl
     * @var string $subscriptionsUrl
     * @var string $organizationsUrl
     * @var string $reposUrl
     * @var string $eventsUrl
     * @var string $receivedEventsUrl
     * @var string $type
     * @var bool $siteAdmin
     */
    private function __construct(
        public string $login,
        public int $id,
        public string $nodeId,
        public string $avatarUrl,
        public string $gravatarId,
        public string $url,
        public string $htmlUrl,
        public string $followersUrl,
        public string $followingUrl,
        public string $gistsUrl,
        public string $starredUrl,
        public string $subscriptionsUrl,
        public string $organizationsUrl,
        public string $reposUrl,
        public string $eventsUrl,
        public string $receivedEventsUrl,
        public string $type,
        public bool $siteAdmin,
    ){}
}

class Committer {
    /**
     * @var string $login
     * @var int $id
     * @var string $nodeId
     * @var string $avatarUrl
     * @var string $gravatarId
     * @var string $url
     * @var string $htmlUrl
     * @var string $followersUrl
     * @var string $followingUrl
     * @var string $gistsUrl
     * @var string $starredUrl
     * @var string $subscriptionsUrl
     * @var string $organizationsUrl
     * @var string $reposUrl
     * @var string $eventsUrl
     * @var string $receivedEventsUrl
     * @var string $type
     * @var bool $siteAdmin
     */
    private function __construct(
        public string $login,
        public int $id,
        public string $nodeId,
        public string $avatarUrl,
        public string $gravatarId,
        public string $url,
        public string $htmlUrl,
        public string $followersUrl,
        public string $followingUrl,
        public string $gistsUrl,
        public string $starredUrl,
        public string $subscriptionsUrl,
        public string $organizationsUrl,
        public string $reposUrl,
        public string $eventsUrl,
        public string $receivedEventsUrl,
        public string $type,
        public bool $siteAdmin,
    ){}
}

class Parents {
    /**
     * @var string $url
     * @var string $sha
     */
    private function __construct(
        public string $url,
        public string $sha,
    ){}
}

class CommitAuthor {
    /**
     * @var string $name
     * @var string $email
     * @var string $date
     */
    private function __construct(
        public string $name,
        public string $email,
        public string $date,
    ){}
}

class CommitCommitter {
    /**
     * @var string $name
     * @var string $email
     * @var string $date
     */
    private function __construct(
        public string $name,
        public string $email,
        public string $date,
    ){}
}

class CommitTree {
    /**
     * @var string $url
     * @var string $sha
     */
    private function __construct(
        public string $url,
        public string $sha,
    ){}
}

class CommitVerification {
    /**
     * @var bool $verified
     * @var string $reason
     * @var mixed $signature
     * @var mixed $payload
     */
    private function __construct(
        public bool $verified,
        public string $reason,
        public mixed $signature,
        public mixed $payload,
    ){}
}

