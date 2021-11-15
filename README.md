
# Introduction

This output is for Penbrothers Technical Exam

## Installation

Clone the repository

    git clone git@github.com:arjohnqafsignal/pb-exam.git

Switch to the repo folder

    cd pb-exam

Install all the dependencies using composer

    composer install

Install javascript dependencies using NPM/Yarn

    npm install / yarn install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone git@github.com:arjohnqafsignal/pb-exam.git
    cd pb-exam
    composer install
    npm install / yarn install
    cp .env.example .env
    php artisan key:generate
    
**Make sure you set the correct database connection information before running the migrations**

    php artisan migrate
    php artisan serve
