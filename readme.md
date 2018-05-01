# Admin and Users

This application is a demo. I'm using context based modules, approach that I took out of the Elixir/Phoenix environment.

## Installation

You will need Docker and docker-compose tools to run this example.

- Clone this repo
- Run `cp .env.example .env`
- Run `docker-compose up -d`
- Run `docker-compose exec app bash` to get a shell inside the application container and run the migrations and passport install commands on it:
    - Run `php artisan key:generate` (for local env only)
    - Run `php artisan migrate`
    - Run `php artisan db:seed` (for local env only)
    - Run `php artisan passport:install`
    - Run `exit` to get out of the container
- Pull and compile assets:
    - Run `yarn && yarn dev`
- Load the application in the browser at [http://localhost](http://localhost). You can use these credentials to authenticate 
    - Email: `admin@example.com`
    - Pass: `secret`
- Generate the API documentation (TODO):
    - Run `composer generate:api-docs`
    - Access [http://localhost/api/docs](http://localhost/api/docs)
    
## Documentation

- You can find some Sequence Diagrams [here](./resources/docs/images)
- The ER model also can be found [here](./resources/docs/images/er-model.png)