Angular test
========================

 We need a single page app in which user should be able:
* upload, edit and delete pictures wit labels
* see all uploaded pictures (label must be in images alt attribute)

Backend must be REST API based on Symfony2.
GUI must be made with AngularJS.

Running the project
----------------------------------

1. clone repository
2. composer install
3. php app/console server:run
4. naviate to page: http://127.0.0.1:8000/frontend.html

P.S. Directories (app/cache, app/log, web/images) must be writable