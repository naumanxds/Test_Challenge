# Test Challenge

This project consists of two microservices, `notifications` and `users`, both developed in Symfony, along with a RabbitMQ message broker and a PostgreSQL database. This README provides you with two installation metods first one is using docker and second one is via simple lamp stack.

## Project Video Working Demo

Here is a short demo video of installation and working application [loom_video](https://www.loom.com/share/6e7f8fd8e6554da8a456e5de115961f1?sid=ed0e76b1-fad4-48c9-909c-e6e3360cb755)

## Directory Structure.

```bash
.
├── docker-compose.yaml
├── notifications
│   ├── Dockerfile
│   └── (other application files)
├── users
│   ├── Dockerfile
│   └── (other application files)
└── README.md
```

## 1 Installation Using Docker

### 1.1 Prerequisites

Following are the porject dependencies for this method to work.
Before you begin, ensure you have the following installed on your machine:

 - Docker
 - Docker Compose
 - Make sure that in your system these ports are not used by anyother applicaton in order to avoid conflict `8000` `8001` `5433` `5673` `15673`

### 1.2 Post Installation what you will have

 - Network by the name of `test_challenge_nauman919_network` to verify use the command  `docker network ls`

 - Containers by the name of `nauman919_notifications` , `nauman919_users` , `nauman919_rabbitmq` , `nauman919_postgres` to verify use command `docker container ls -a`

 - Images by the name of `test_challenge-notifications` , `test_challenge-users` , `postgres` , `rabbitmq` to verify use the command `docker image ls -a`

### 1.3 Installation Steps

**Step 1:** Open up a terminal and Clone the Repository

```sh
 git clone git@github.com:naumanxds/Test_Challenge.git

 # OR

 git clone https://github.com/naumanxds/Test_Challenge.git
```

**Step 2:** Build and start the containers using Docker Compose:

```sh
 docker-compose up --build
```

**Step 3:** Run the Consumer

Once you have verified the installation we need to run our consumer in the notifications service for this `Open a new Terminal` and follow below steps

```sh
 docker exec -it nauman919_notifications bash

 # Then run

 ./bin/console messenger:consume

 # DO NOT CLOSE THE TERMINAL
```

**Step 4:** Accessing the Services

 - Open your browser and goto [http://localhost:8001](http://localhost:8001) you will see a user create form.
 - Open your browser and goto [http://localhost:15673](http://localhost:15673) use username: `guest` and password: `guest` to access te RabbitMq Dashboard.


**Step 5:** `IF` you want to verify data in database follow the follwing

```
 psql -h localhost -p 5433 -d nauman919_users_db -U postgres
```

It will ask for Password which is **adminadmin** Copy and paste it as it is in the terminal and hit enter. After that do following

```

 \x

 # once you paste \x and hit enter make sure it says "Expanded display is on."

 # Hit enter and the paste the below query and finally hit enter again.

 SELECT * FROM "user";
```

### 1.4  Troubleshooting

Incase if the instllation is not done properly make sure that you have vendor folder created properly in your container. To do that follow the following steps

 - For Users service
```sh
 docker exec -it nauman919_users bash

 # Then check if vendor folder exists or not.

 # If not run the following command

 composer install
```

 - For Notifications service
```sh
 docker exec -it nauman919_notifications bash

 # Then check if vendor folder exists or not.

 # If not run the following command

 composer install
```

## 2 Installation Using LAMPP

### 2.1 Prerequisites

Following are the porject dependencies for this method to work.
Before you begin, ensure you have the following installed on your machine:

 - Apache installed
 - Php 8.2 installed on your system with all the basic php extensions
 - Postgres Sql installed
 - Rabbitmq installed.
 - composer installed
 - Make sure that in your system these ports are not used by anyother applicaton in order to avoid conflict `8000` `8001`

### 1.3 Installation Steps

**Step 1:** Clone the Repository

```sh
 git clone git@github.com:naumanxds/Test_Challenge.git

 # OR

 https://github.com/naumanxds/Test_Challenge.git
```

**Step 1:** Setup users service

- From the root folder of the project goto `users` folder and open a `terminal`

- Copy the following change `USERNAME` and `PASSWORD` accordingly and paste it inside the .env file
```env
APP_ENV=dev

APP_SECRET=669c67bab6240e7c87943b065b237d07

DATABASE_URL="postgresql://USERNAME:PASSWROD@127.0.0.1:5432/micro_service_app_users?serverVersion=16&charset=utf8"

MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages

CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'
```

- Make a database in postgres by the name of `micro_service_app_users`

- run the following command

```sh
 # First Run

 composer install

 # Then

 ./bin/console doctrine:migrations:migrate

 # Then

 ./bin/console cache:clear

 # Then

 php -S localhost:8000 -t public
```

- Once the above commands are executed keep the terminal running as the app server is running in it.






**Step 2:** Setup notifications service

- From the root folder of the project goto `notifications` folder and open a `terminal`

- Copy the following change `USERNAME` and `PASSWORD` accordingly and paste it inside the .env file
```env

APP_ENV=dev

APP_SECRET=70450bc31a829c618ec361b92d5ccb7c

DATABASE_URL="postgresql://postgres:adminadmin@127.0.0.1:5432/micro_service_app_notifications?serverVersion=16&charset=utf8"

MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
```

- Make a database in postgres by the name of `micro_service_app_notifications`

- run the following command

```sh
 # First Run

 composer install

 # Then

 ./bin/console doctrine:migrations:migrate

 # Then

 ./bin/console cache:clear

 # Then

 php -S localhost:8001 -t public
```

- Once you have executed the above commands keep the server running in the terminal and open up a new terminal in `notifications` folder and run the following command

```sh
 ./bin/console messenger:consume
```

- Once you have followed all the steps above you will be able to access the app in the browser on [http://localhost:8001]()
