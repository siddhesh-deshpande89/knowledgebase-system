
### Objective
In this example we will setup a blog service with Laravel 9.x.
- Setup a blog service that is capable running on cluster of web servers.
- REST API endpoints to create, view, rate articles.
- 100% Unit test coverage
- Integration tests using sqlite, database factory and seeders.
- Redis caching to cache the data
- Job Dispatching for POST queries such as view count update.

### Setup
- Go to the project root folder and start docker container with the following command:

```cd blog-service && ./vendor/sail up -d```
- Navigate to http://localhost in your web browser.
