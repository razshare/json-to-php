<?php
class Payload {
    /**
     * @param string $url
     * @param string $sha
     * @param string $nodeId
     * @param string $htmlUrl
     * @param string $commentsUrl
     * @param Commit $commit
     * @param Author $author
     * @param Committer $committer
     * @param array<Parents> $parents
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

class CommitAuthor {
    /**
     * @param string $name
     * @param string $email
     * @param string $date
     */
    private function __construct(
        public string $name,
        public string $email,
        public string $date,
    ){}
}

class CommitCommitter {
    /**
     * @param string $name
     * @param string $email
     * @param string $date
     */
    private function __construct(
        public string $name,
        public string $email,
        public string $date,
    ){}
}

class CommitTree {
    /**
     * @param string $url
     * @param string $sha
     */
    private function __construct(
        public string $url,
        public string $sha,
    ){}
}

class CommitVerification {
    /**
     * @param bool $verified
     * @param string $reason
     * @param mixed $signature
     * @param mixed $payload
     */
    private function __construct(
        public bool $verified,
        public string $reason,
        public mixed $signature,
        public mixed $payload,
    ){}
}

class Commit {
    /**
     * @param string $url
     * @param CommitAuthor $author
     * @param CommitCommitter $committer
     * @param string $message
     * @param CommitTree $tree
     * @param int $commentCount
     * @param CommitVerification $verification
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
     * @param string $login
     * @param int $id
     * @param string $nodeId
     * @param string $avatarUrl
     * @param string $gravatarId
     * @param string $url
     * @param string $htmlUrl
     * @param string $followersUrl
     * @param string $followingUrl
     * @param string $gistsUrl
     * @param string $starredUrl
     * @param string $subscriptionsUrl
     * @param string $organizationsUrl
     * @param string $reposUrl
     * @param string $eventsUrl
     * @param string $receivedEventsUrl
     * @param string $type
     * @param bool $siteAdmin
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
     * @param string $login
     * @param int $id
     * @param string $nodeId
     * @param string $avatarUrl
     * @param string $gravatarId
     * @param string $url
     * @param string $htmlUrl
     * @param string $followersUrl
     * @param string $followingUrl
     * @param string $gistsUrl
     * @param string $starredUrl
     * @param string $subscriptionsUrl
     * @param string $organizationsUrl
     * @param string $reposUrl
     * @param string $eventsUrl
     * @param string $receivedEventsUrl
     * @param string $type
     * @param bool $siteAdmin
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
     * @param string $url
     * @param string $sha
     */
    private function __construct(
        public string $url,
        public string $sha,
    ){}
}

