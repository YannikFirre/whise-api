# Whise API

PHP client for the [Whise](https://www.whise.eu) API. For terms of use and API credentials, refer to the official
documentation.

## Installation

`composer require fw4/whise-api`

## Usage

```php
use Whise\Api\WhiseApi;

$api = new WhiseApi();

// Retrieve existing access token from storage (getAccessTokenFromDataStore to be implemented)
$accessToken = getAccessTokenFromDataStore();

if (empty($accessToken)) {
    // Request and store new access token (saveAccessTokenToDataStore to be implemented)
    $accessToken = $api->requestAccessToken('username', 'password');
    saveAccessTokenToDataStore($accessToken);
} else {
    $client->setAccessToken($accessToken);
}
```

All endpoints are provided as methods of the WhiseApi class. For more information about available endpoints and
response format, refer to the official API documentation.

### Available endpoints

Use the following methods to access available endpoints:

#### Administration

```php
$api->admin()->clients()->list($parameters);
$api->admin()->clients()->settings($parameters);
$api->admin()->clients()->token($parameters);
$api->admin()->offices()->list($parameters);
$api->admin()->representatives()->list($parameters);
```

#### Estates

```php
$api->estates()->list($filter, $sorting, $fields);
$api->estates()->update($parameters);
$api->estates()->create($parameters);
$api->estates()->delete($id);
$api->estates()->regions()->list($parameters);
$api->estates()->usedCities()->list($filter);
$api->estates()->pictures()->upload($estate_id, $file);
$api->estates()->pictures()->delete($estate_id, $picture_id);
$api->estates()->documents()->upload($estate_id, $file);
$api->estates()->documents()->delete($estate_id, $document_id);
```

#### Contacts

```php
$api->contacts()->list($filter, $sorting, $fields);
$api->contacts()->update($parameters);
$api->contacts()->create($parameters);
$api->contacts()->delete($id);
$api->contacts()->origins()->list($parameters);
$api->contacts()->titles()->list($parameters);
$api->contacts()->types()->list($parameters);
```

#### Calendars

```php
$api->calendars()->list($filter, $sorting, $fields, $aggregation);
$api->calendars()->create($parameters);
$api->calendars()->delete($id);
$api->calendars()->update($parameters);
$api->calendars()->actions()->list($parameters);
```

#### Activity

```php
$api->activities()->calendars($filter, $aggregation);
$api->activities()->histories($filter, $aggregation);
$api->activities()->audits($filter, $aggregation);
$api->activities()->historyExports($filter, $aggregation);
```

### Pagination

Endpoints that retrieve multiple items return a traversable list of objects. Pagination for large lists happens
automatically.

```php
$estates = $api->estates()->list();

// Traversing over the response takes care of pagination in the background
foreach ($estates as $estate) {
	echo $estate->name . PHP_EOL;
}
```

## License

`fw4/whise-api` is licensed under the MIT License (MIT). Please see [LICENSE](LICENSE) for more information.