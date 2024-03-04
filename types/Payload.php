<?php
class Payload {
    /**
     * @var int $id
     * @var string $nodeId
     * @var string $url
     * @var string $htmlUrl
     * @var string $body
     * @var User $user
     * @var string $createdAt
     * @var string $updatedAt
     * @var string $issueUrl
     * @var string $authorAssociation
     */
    private function __construct(
        public int $id,
        public string $nodeId,
        public string $url,
        public string $htmlUrl,
        public string $body,
        public User $user,
        public string $createdAt,
        public string $updatedAt,
        public string $issueUrl,
        public string $authorAssociation,
    ){}
}

class User {
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

