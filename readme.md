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

## How To
