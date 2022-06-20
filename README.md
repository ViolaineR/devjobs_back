# devjobs_back

## Table of Contents
* [General info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)

## General info
This project is an application to manage job offers and partner companies.

## Technologies
Project is created with:
* PHP version: 7.4.29
* Symfony version: 5.4.*
* Composer version: 2.3.7

## Setup
To clone the project do the following commands:
```
git clone https://github.com/ViolaineR/devjobs_back.git
```
Go to the folder containing the project and install Composer:
```
cd devjobs_back
composer install
```
Open the env.local file and modify the database url with your information (e.g. db_user, db_password and db_name) in the example below:
```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
```
Then, you can create the database with the symfony doctrine by using the command:
```
symfony console doctrine:database:create
```
If there are no migrations in the project's migration folder run this command:
```
symfony console make:migration
```
To update the database, run the migrations with the command:
```
symfony console doctrine:migrations:migrate
```
Finally, start the project locally with:
```
symfony serve
```