## Bootstrap blog-home on Laravel 5.3

[![Build Status](https://travis-ci.org/jeanquark/blog-home.svg?branch=test)](https://travis-ci.org/jeanquark/blog-home)

Ever wanted to experiment the famous [bootstrap blog-home](http://startbootstrap.com/template-overviews/blog-home) live? This project is a fully functional blog application based on the bootstrap blog-home template and the bootstrap 3 [adminsb](http://startbootstrap.com/template-overviews/sb-admin) theme for the admin area. It is written in PHP and uses [Laravel 5.3](https://laravel.com) as a framework.

![homepage](https://github.com/jeanquark/blog-home1/raw/master/public/homepage.jpg "Homepage")

## Installation

You need to have a local server working on your computer to run this project locally. We recommand using [Homestead](https://laravel.com/docs/master/homestead) but you can also use [Xampp](https://www.apachefriends.org/fr/index.html), which is less complicated to install. So first make sure a local server is running on your computer. Next create the database on which blog posts will reside (for example using phpmyadmin). Then type the following commands in your favorite CLI (xampp users: make sure your emplacement is the *xampp/htdocs* folder):

Clone the repo:
```
git clone https://github.com/jeanquark/blog-home.git
```

Move to the newly created folder and install all dependencies:
```
cd blog-home
composer install
```

Open the .env.example file, edit it to match your database name, username and password (required step) as well as your email credentials (optional, but required for the contact form to work) and save it as .env file. Then build tables with command:
```
php artisan migrate
```

Now fill the tables:
```
php artisan db:seed
```

Generate application key 
```
php artisan key:generate
```

And voilà! Now you should be able to test the application. Go to the login page and enter the provided credentials. Then click on the top nav email address to get to the admin area. Enjoy!


## Features
1. Create blog posts that consist of formatted text and images.
2. Link those posts to tags for quick reference.
3. Allow posts to be commented and replies to these comments.
4. Search among posts based on text excerpt.
5. Manage users.
6. Send a message with the contact form.


## Contact Form
The contact form can be used as is. I have preconfigured it for a Gmail address in the *.env.example* file. All you have to do is entering your actual email address and password. Have fun!


## Screenshots
Login:
![Login](https://github.com/jeanquark/blog-home/raw/master/public/login.jpg "Login")

Post:
![Post](https://github.com/jeanquark/blog-home/raw/master/public/post.jpg "Post")

Comment:
![Comment](https://github.com/jeanquark/blog-home/raw/master/public/comment.jpg "Comment")

Admin:
![Admin](https://github.com/jeanquark/blog-home/raw/master/public/admin.jpg "Admin")

List posts:
![Posts](https://github.com/jeanquark/blog-home/raw/master/public/posts.jpg "Posts List")


## Packages
* [sentinel](https://github.com/cartalyst/sentinel) for authentication and authorization
* [image-validator](https://github.com/cviebrock/image-validator) for image upload validation
* [active](https://github.com/letrunghieu/active) for active class on current url
* [hashids](https://github.com/ivanakimov/hashids.php) for quick hash of user ids


## License

Please refer to the [blog-home](http://startbootstrap.com/template-overviews/blog-home) license.