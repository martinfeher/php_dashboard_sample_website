## PHP dashboard sample project
- autor: Martin Fehér

## Prerequisites
- PHP >= 7.2

## Installation
- clone, download project
- webserver settings:
- website public folder: /public
- website public root file: /public/index.php
- create mysql database table structure apply code in the /resources/myslq_create_tables.sql

## Description
- PHP, MySQL appliclation to present CRUD operations, search and order data

- In the application on the first page /work_positions the user can display a list of the work positions and is able to create, update and delete the data. A work position has two parameters title, description. The user can search the work position by the parameters.

- On the second page /people.php the user can list, add, update, delete the data. The user can relate the existing work position to a person. A person has multiple parametres: first name, surname, title, email, phone and a related work position.
There is an option to search and re-order the data by their parameters.
