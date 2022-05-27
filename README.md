**<h1 align="center">HealthKerd</h1>**

<div align="center">

![WIP](https://img.shields.io/badge/WIP-red)
![Phase](https://img.shields.io/badge/phase-alpha-red)
[![Status](https://img.shields.io/badge/status-active-success.svg)]()
[![GitHub Issues](https://img.shields.io/github/issues/kerdou/HealthKerd)](https://img.shields.io/github/issues/kerdou/HealthKerd)
[![License](https://img.shields.io/badge/license-ODbL-blue.svg)](/LICENSE)

</div>

---

<p align="center"> HealthKerd is a web application meant to keep track of my medical appointments and treatments.</p>

## 📝 Table of Contents

- [About](#about)
- [Highlights](#highlights)
- [Usage](#usage)
- [Built Using](#built_using)
- [TODO](#todo)
- [Author](#author)
- [Acknowledgments](#acknowledgement)

## 🧐 About <a name = "about"></a>

If you landed on this page, it probably means you came from my portfolio.

Here is a list of things you should know before getting any further.

### :dart: What HealthKerd is <a name = "is"></a>

- Very much work in progress

- A web application meant to keep track of my medical appointments and treatments.

- Relying on a MySQL database made of 64 tables using the InnoDB engine

- Far from ideal from the UI/UX standpoint, because the first objective was about gaining experience regarding backend and database

- A great opportunity to work on a bigger project without any big framework in order to strenghten my basics. When it comes to learning, I don't believe in shortcuts

- My first personal project

- A sandbox allowing me to make my mistakes and learn from them

- A project that will evolve on the long run

- A great addition to my portfolio


### :no_entry: What HealthKerd is ~~**NOT**~~<a name = "isnot"></a>

- Meant to be used by anybody but me for legal reasons, this is why the database won't be provided

- Relying on frontend or backend frameworks such as React or Symfony, but this will change in the future

## :star: Highlights <a name = "highlights"></a>

### PHP, TS & Sass files location

- PHP can be found at **```src/```**
- TypeScript can be found at **```dev/JS & TS/TS/```**
- Sass can be found at **```dev/Sass/```**

### Class UML

- They can be found at **```dev/UML/classULM/```**
- They come as **```.plantuml```** files
### Database creation

- Since that app was created in order to help me keeping track of my medical appointments and treatments, everything is based on a real world study case
- It was created through an iterative process that cycled until the database was able to handle every case I encountered or could encounter
- This explains why everything is split between so many tables

### Medical events display

- It only takes 2 connections to the database in order to display the medical events
- The first connection gathers the events ids
- The second connection gathers every data from around 40 tables
- This event data collection is managed by a homemade buffer that can be found in **```/src/Model/common/PdoBufferManager.php```**
- This data collection doesn't use an ORM but still relies on a homemade mapping system in order to reach many tables with adaptabilty in mind during its creation
- The mapping system has the advantage to make the Model files smaller and more readable. It is a splitted in **```/src/Model/```**
- Since the data is very much splitted between the different tables, it takes a lot of work to recombine it all. That part is done in **```src/Processor/medic/event/```** before being sent to the View

### Login page

- If you try to log in with a wrong user name and/or a wrong password, you will have an adapted error message
- If you cumulate too many wrong login attemps during a certain period of time, your IP will be banned for 48 hours due to an overwatch from Fail2Ban

### Doctor name

- Depending on the doctor's title which could be Dr/Mr/Mrs/Ms or none, the name displayed will change accordingly
- Doctor medical speciality and office form
- Data on this page comes from an AJAX request
- Once the modification is confirmed, the data also get to PHP through AJAX
- Clicking on a yellow badge below "Spécialités médicales assignées" will make it vanish and the speciality name will be put back into the "Spécialités médicales disponibles" dropdown menu
- Clicking on "Ajouter" will remove the selected medical speciality from the dropdown menu and make it appear as a badge under "Spécialités médicales assignées"
- Clicking on a card below "Cabinets médicaux assignés" will remove it. If the removed badge contains a speciality present in "Spécialités médicales assignées", it will reappear in "Cabinets médicaux compatibles"
- Clicking on a card below "Cabinets médicaux compatibles" will transfer it under "Cabinets médicaux assignés"

## 🎈 Usage <a name = "usage"></a>

### Demonstration account

- The login page provides the user name and password for the demonstration account
- It gives access to a restricted account which doesn't allow to modify the user's informations
- Some links won't react to your clicks, they are just placeholders

### Doctors pages

- The doctors who's names are followed by a :key: can't be modified
- You can create, modify and delete doctors which don't have a :key:
- You can also edit their medical specialities and their attached medical offices
## ⛏️ Built Using <a name = "built_using"></a>

- [MySQL](https://www.mysql.com/) - Database
- [PHP 8](https://www.php.net/) - Backend language
- [Composer](https://getcomposer.org/) - Dependency Manager for PHP
- [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) - PHP Linter
- [TypeScript](https://www.typescriptlang.org/) - Frontend language
- [NPM](https://www.npmjs.com/) - JavaScript package manager
- [ESLint](https://eslint.org/) - JavaScript Linter
- [Lodash](https://lodash.com/) - JavaScript multipurpose library
- [date-fns](https://date-fns.org/) - JavaScript date utility library
- [Bootstrap](https://getbootstrap.com/) - Web design framework
- [Sass](https://sass-lang.com/) - CSS Pre-processor
- [Live Sass Compiler](https://marketplace.visualstudio.com/items?itemName=ritwickdey.live-sass) - Sass watcher and compiler
- [Webpack](https://webpack.js.org/) - Frontend bundler

## :heavy_check_mark: TODO <a name = "todo"></a>

- Add breadcrumbs on many pages in order to ease navigation

- Rewrite frontend while including React

- Rewrite backend while including Symfony

- Add new pages and features once those frameworks will be included

- Adding automatic tests later on

## ✍️ Author <a name = "author"></a>

- [@kerdou](https://www.linkedin.com/in/gautier-le-hir-78796515b/) - Idea & Initial work. [Portfolio](https://kerdapp.ddns.net/)


## 🎉 Acknowledgements <a name = "acknowledgement"></a>

- Hat tip to anyone whose code was used
- The various health issues, medical appointments and treatments that gave me this idea
- The remarks and recommandations given by the jury during the defense for my web dev professionnal credential
