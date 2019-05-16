# GIT API CLIENT

**Current Status: `ALPHA.1`**

This is an API Client for Custom GIT server. This Repository is based on [Joachim M. Giaever's `gogs-php-api-client`](https://git.giaever.org/joachimmg/gogs-php-api-client). This Repository retains the Orignal Author of the Project.
> **Q:** What is GOGS?
>
> **A:** GOGS is a Painless self-hosted Git Server written in GO Programming Language. It is so light weight that it can even run on 512mb RAM in a Docker Instance. It has a Front-end Interface as well as REST API (`v1`) that this client consumes.

## Requirements

- `laravel/laravel:^5.7` (Laravel 5.7+)
- `PHP:^7.0` (PHP 7.0+)
- `guzzlehttp/guzzle:^6.2` (Guzzle 6.2+)

> This Package/Library might work with lower Versions of the above mentioned products too but still have not been tested.

## Installation

### 1. Composer

```bash
composer require clippedcode/gogs:dev-master
```

### 2. Laravel

- Register Service Provider:
  > As it require minimum Laravel `5.7+` Installation, therefore the `Clippedcode\Git\GitServiceProvider::class` will be registered automatically.

- Publish Configuration:

  ```shell
  php artisan vendor:publish --tag=clippedcode_git_config
  ```

- Edit Configurations: Edit the configurations according to your needs.

## Usage

Read the [API Readme](./README-API.md) for a detailed Information of working with this repository.

Also Consider Reading [GOGS API Documentation](https://github.com/gogs/docs-api).

## Contributions

Contributions to this Repository are welcomed through **Pull Requests** and **Issues**.

## License

This repository is Licensed Under `MIT License`. Learn More about the [LICENSE](./LICENSE).

## Credits

- **Joachim M. Giaever**: [GOGS PHP API Client](https://git.giaever.org/joachimmg/gogs-php-api-client)
