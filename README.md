<p align="center"><a href="https://dzencode.com/ua" target="_blank"><img src="public/image/dZENcode-Logo.png" width="200" alt="dZENcode Logo"></a></p>

# Test task for dZENcode
## Spa-app: Comments

- Test task
    * [Installation](#installation)
    * [Issues](#issues)
    * [Documentation](#documentation)
# Installation

#### Make sure you are in the directory with the files: `.env` and `composer.json` ####


- Make changes to the `.env` file (add your database)


- Install all dependencies:
  `npm install`


- Install composer:
  `composer install`


- Generate key:
  `php artisan key:generate`


- Run migrate:
  `php artisan migrate`


- Build project:
  `npm run build`


- Run serve:
  `php artisan serve`


# Issues

1. If you have error like this `('/test.task.commets/.env'): Failed to open stream: No such file or directory`

Rename `.env.example` to `.env`

# Documentation
EER Diagram for this app ``storage/app/public/EER_Diagram-Comments_App.mwb``

