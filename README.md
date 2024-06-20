## Appointment Project

Run below Commands:

1. copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```

2. build and run the project using Docker:
   ```bash
   docker-compose up --build
   ```

3. Install the composer packages
   ```bash
   make composer-install
   ```

4. Migrate and seed tables
   ```bash
   make migrate
   ```

5. url of project  `http://localhost:8081/`

- Run Tests
   ```bash
  make run-test
   ```

___

## routes

|  #  |         url          | method |             description             |
|:---:|:--------------------:|:------:|:-----------------------------------:|
|  1  |    /appointments     |  GET   |      Get list of Appointments       |
|  1  | /appointments/create |  POST  | Create New Appointment for the user |
|  1  | /appointments/cancel | PATCH  |        Cancel an Appointment        |