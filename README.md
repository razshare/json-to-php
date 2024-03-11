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
```

> [!NOTE]
> - The input file name (excluding the `.json` extension, of course) must be a `valid` Php `class name`.
> - The resulting Php file is not formatted properly, you may want to run php-cs-fixer on it.
