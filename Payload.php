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
    public static function create(
        int $id,
        string $nodeId,
        string $url,
        string $htmlUrl,
        string $body,
        User $user,
        string $createdAt,
        string $updatedAt,
        string $issueUrl,
        string $authorAssociation,
    ):self {
        return new self(
            id:$id,
            nodeId:$nodeId,
            url:$url,
            htmlUrl:$htmlUrl,
            body:$body,
            user:$user,
            createdAt:$createdAt,
            updatedAt:$updatedAt,
            issueUrl:$issueUrl,
            authorAssociation:$authorAssociation,
        );
    }

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
    public static function create(
        string $login,
        int $id,
        string $nodeId,
        string $avatarUrl,
        string $gravatarId,
        string $url,
        string $htmlUrl,
        string $followersUrl,
        string $followingUrl,
        string $gistsUrl,
        string $starredUrl,
        string $subscriptionsUrl,
        string $organizationsUrl,
        string $reposUrl,
        string $eventsUrl,
        string $receivedEventsUrl,
        string $type,
        bool $siteAdmin,
    ):self {
        return new self(
            login:$login,
            id:$id,
            nodeId:$nodeId,
            avatarUrl:$avatarUrl,
            gravatarId:$gravatarId,
            url:$url,
            htmlUrl:$htmlUrl,
            followersUrl:$followersUrl,
            followingUrl:$followingUrl,
            gistsUrl:$gistsUrl,
            starredUrl:$starredUrl,
            subscriptionsUrl:$subscriptionsUrl,
            organizationsUrl:$organizationsUrl,
            reposUrl:$reposUrl,
            eventsUrl:$eventsUrl,
            receivedEventsUrl:$receivedEventsUrl,
            type:$type,
            siteAdmin:$siteAdmin,
        );
    }

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

