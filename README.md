## PHP dashboard sample project
- autor: Martin Fehér

## Prerequisites
- PHP >= 7.2

## Installation
- to clone, download project you can run the command git clone https://github.com/martinfeher/php_dashboard_sample_website.git
- webserver settings:
- website public folder: /public
- website public root file: /public/index.php
- to create mysql database table structure apply code in the /resources/myslq_create_tables.sql in your local or production environment
- to run application in the local environment, go to the /public folder and run "php -S 127.0.0.1:8000"

## Description
- PHP, MySQL appliclation to present CRUD operations, search and order data

- In the application on the first page /work_positions the user can display a list of the work positions and is able to create, update and delete the data. A work position has two parameters title, description. The user can search the work position by the parameters.

- On the second page /people.php the user can list, add, update, delete the data. The user can relate the existing work position to a person. A person has multiple parametres: first name, surname, title, email, phone and a related work position.
There is an option to search and re-order the data by their parameters.
