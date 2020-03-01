# REST API for CD Rental with LUMEN 5.5
&copy; 2020 Mohammad Daffa
> This repository was specially made for Jojonomic Backend Engineer Test

## Features
* Admin Authorization for Adding new CD or Edit existing CD
* User Login and Register
* User Authorization to Access all the User's Feature :
  - User can see all the CD that can be rented
  - User can rent a CD
  - User can see what they rent
  - User can return a CD
  - User will know the rent price when returning the CD

## API Routes
* **Admin Routes**
  1. **Add new CD**
     - `METHOD: POST` `PATH: /add` `PARAM: title, rate, category, quantity` `HEADER: Auth-Bearer Token`
  2. **Edit existing CD**
     - `METHOD: PUT` `PATH: /cd/{id}` `PARAM: title, rate, category, quantity` `HEADER: Auth-Bearer Token`
  3. **Delete existing CD**
     - `METHOD: DELETE` `PATH: /cd/{id}` `PARAM: - ` `HEADER: Auth-Bearer Token`
  4. **Show list returned Rent**
     - `METHOD: GET` `PATH: /rent/returned` `PARAM: - ` `HEADER: Auth-Bearer Token`
  5. **Show list not yet returned Rent**
     - `METHOD: GET` `PATH: /rent/notreturned` `PARAM: - ` `HEADER: Auth-Bearer Token`
* **User Routes**
  1. **Get list CD**
     - `METHOD: GET` `PATH: /cd` `PARAM: - ` `HEADER: Auth-Bearer Token`
  2. **Get specific CD by ID**
     - `METHOD: GET` `PATH: /cd/{id}` `PARAM: - ` `HEADER: Auth-Bearer Token`
  3. **Rent a CD**
     - `METHOD: POST` `PATH: /cd/{id}/rent` `PARAM: - ` `HEADER: Auth-Bearer Token`
  4. **Check User's rent list**
     - `METHOD: GET` `PATH: /user/rent` `PARAM: - ` `HEADER: Auth-Bearer Token`
  5. **Check User's specific rent**
     - `METHOD: GET` `PATH: /user/rent/{id}` `PARAM: - ` `HEADER: Auth-Bearer Token`
  6. **Return rented CD**
     - `METHOD: POST` `PATH: /user/return/{id}` `PARAM: - ` `HEADER: Auth-Bearer Token`
* **Auth Routes**
  1. **Register**
     - `METHOD: POST` `PATH: /register` `PARAM: name, username, password` `HEADER: - `
  1. **Login**
     - `METHOD: POST` `PATH: /login` `PARAM: username, password` `HEADER: - `

## How To Run the App
First, clone the repo:
```bash
$ git clone https://github.com/daffa99/lumen-backend.git
```
#### Install dependencies
```
$ cd lumen-backend
$ composer install
```
#### Configure the Environment
Create `.env` file:
```
$ cat .env.example > .env
```
You can also adjust the database variables
#### Create the databases
```bash
mysql> CREATE DATABASE jojonomic;
```
Test database:
```bash
mysql> CREATE DATABASE jojonomic_test;
```
#### Run the Artisan migrate command
```bash
$ php artisan migrate
```
#### Run the App
```bash
$ php -S localhost:8000 -t public
```
## Recommended API Route Step
#### Login Admin
`POST: /login` with PARAMETERS: 
```
{
	"username":"admin",
	"password":"admin123"
}
``` 
and copy the obtined token
#### Add new CD
`POST: /add` paste the token into `Authorization with type: Bearer Token`, and input PARAMETERS: 
```
{
    "title": "Parasite",
    "rate": "3000",
    "category": "Thriller",
    "quantity": "2"
}
```
#### Add more quantity in CD-1
`PUT: /cd/1` paste the token into `Authorization with type: Bearer Token`, and input PARAMETERS:
```
{
    "quantity": "13"
}
```
#### You can add more CD to populate the CD list
#### Register User
`POST: /register` with PARAMETERS: 
```
{
	"name":"Jojonomic",
	"username":"jojonomic",
	"password":"rahasia"
}
``` 
#### Login User
`POST: /login` with PARAMETERS: 
```
{
	"username":"jojonomic",
	"password":"rahasia"
}
``` 
and copy the obtined token
#### See CD List
`GET: /cd` paste the token into `Authorization with type: Bearer Token`
#### See CD-1
`GET: /cd/1` paste the token into `Authorization with type: Bearer Token`
#### Rent CD-1
`POST: /cd/1/rent` paste the token into `Authorization with type: Bearer Token`
#### See User's rented CD
`POST: /user/rent` paste the token into `Authorization with type: Bearer Token`
#### Return the CD with rent ID 1
`POST: /user/return/1` paste the token into `Authorization with type: Bearer Token`
#### You can try with more admin route such as see the rented CD, delete CD, see the returned CD, and more.
