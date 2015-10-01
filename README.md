Angular test
========================

 We need a single page app in which user should be able:
* upload, edit and delete pictures wit labels
* see all uploaded pictures (label must be in images alt attribute)

Backend must be REST API based on Symfony2.
GUI must be made with AngularJS.

[![Build Status](https://travis-ci.org/gimler/symfony-rest-edition.png?branch=2.3)](https://travis-ci.org/gimler/symfony-rest-edition) [![Total Downloads](https://poser.pugx.org/gimler/symfony-rest-edition/downloads.png)](https://packagist.org/packages/gimler/symfony-rest-edition)
[![License](https://poser.pugx.org/gimler/symfony-rest-edition/license)](https://packagist.org/packages/gimler/symfony-rest-edition)

Welcome to the Symfony REST Edition - a fully-functional Symfony2
application that you can use as the skeleton for your new applications.

This document contains information on how to download, install, and start
using Symfony. For a more detailed explanation, see the [Installation][1]
chapter of the Symfony Documentation.

Running the project
----------------------------------

1. repository already contains all needed files (including vendor files)
2. clone repository
3. run
>> php app/console server:run
4. go to page http://127.0.0.1:8000/frontend.html