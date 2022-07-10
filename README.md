# VK Automatic
## About
This package provides automation of usual VK tasks throw API.

## Usage
To install:

```
composer require mggflow/vk-automatic
```

Example of setting online status for account:

```
$token = "access token for VK API";
$apiVersion = 5.131;

$api = new API($token, $apiVersion);
$task = new EternalOnline($this->api);

// Second argument is null because this task dont need any data.
$result = $task->execute($api);

// Result object fields is different for tasks.
var_dump($result->onlineResult);

```