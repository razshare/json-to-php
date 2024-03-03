# What is this?

This is a cli tool to convert Json data to Php class definitions.

# Usage example

Given the following `Payload.json` file
```json
{
  "id": 1,
  "node_id": "MDEyOklzc3VlQ29tbWVudDE=",
  "url": "https://api.github.com/repos/octocat/Hello-World/issues/comments/1",
  "html_url": "https://github.com/octocat/Hello-World/issues/1347#issuecomment-1",
  "body": "Me too",
  "user": {
    "login": "octocat",
    "id": 1,
    "node_id": "MDQ6VXNlcjE=",
    "avatar_url": "https://github.com/images/error/octocat_happy.gif",
    "gravatar_id": "",
    "url": "https://api.github.com/users/octocat",
    "html_url": "https://github.com/octocat",
    "followers_url": "https://api.github.com/users/octocat/followers",
    "following_url": "https://api.github.com/users/octocat/following{/other_user}",
    "gists_url": "https://api.github.com/users/octocat/gists{/gist_id}",
    "starred_url": "https://api.github.com/users/octocat/starred{/owner}{/repo}",
    "subscriptions_url": "https://api.github.com/users/octocat/subscriptions",
    "organizations_url": "https://api.github.com/users/octocat/orgs",
    "repos_url": "https://api.github.com/users/octocat/repos",
    "events_url": "https://api.github.com/users/octocat/events{/privacy}",
    "received_events_url": "https://api.github.com/users/octocat/received_events",
    "type": "User",
    "site_admin": false
  },
  "created_at": "2011-04-14T16:00:49Z",
  "updated_at": "2011-04-14T16:00:49Z",
  "issue_url": "https://api.github.com/repos/octocat/Hello-World/issues/1347",
  "author_association": "COLLABORATOR"
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
```

> [!NOTE]
> - The input file name (excluding the `.json` extension, of course) must be a `valid` Php `class name`.
> - The resulting Php file is not formatted properly, you may want to run php-cs-fixer on it.
