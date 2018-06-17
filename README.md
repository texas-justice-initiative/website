# Texas Justice Initiative: Website Theme

To learn more about TJI, visit our website at www.texasjusticeinitiative.org

## About this repo

This repo contains the theme files for the Texas Justice Initiative website. The website is built on the WordPress CMS using this custom theme. Below is an outline of the WordPress file structure and theme organization.

## Developing locally

NOTE: These instructions are for TJI team members. If you are not a part of TJI and want to use or contribute to our code, [contact us](http://texasjusticeinitiative.org/contact/) and we'll help you get set up.

1. Install these things 
- [docker](https://www.docker.com/community-edition#/download)
- [docker compose](https://docs.docker.com/compose/install/)
- mysql database viewer like [sqlpro](https://sequelpro.com/download)

2. You'll need SSH access to the server. Ping `#general` on Slack to help you get set up (authorized_keys).

3. Copy the whole wordpress site to your local machine from staging or prod
- Recommend using [SCP](https://linuxacademy.com/blog/linux/ssh-and-scp-howto-tips-tricks/). This command should do the trick:
```
scp -r root@ip.of.our.server:/root/tji/ your_local_directory
```

4. `cd` into the `root/tji/` subdirectory of what you just copied over

5. The server is based on two docker containers -- one for the wordpress server, one for the database (mysql). We use docker-compose to start and connect these two. Locally, you'll need to edit `docker-compose.yml` to change a few things:
- Expose a port on the database so you can access it by adding these lines to the `db` section (under, say, `volumes`):
```
     ports:
       - "3307:3306"
```
- The above will set http://localhost:3307 to point to the database port (3306) on the database container
- Change the exposed port of the wordpress server from 80 to something else, since 80 is a privileged port and takes extra work to get your machine to let you run stuff on. So under `wordpress` change:
```
     ports:
       - "80:80"
```
to something like
```
     ports:
       - "8080:80"
```
- Now, http://localhost:8080 will point to the wordpress server (running on port 80 of the wordpress container)

6. Run `docker-compose up` -- then open your browser to localhost:8080 and you should see the website!
7. However... all the links will be broken. We have one more step to fix this -- editing the database slightly. We need to change two rows in the `wp_options` table -- `siteurl` and `home` need to have an option_value of `http://localhost:8080/` (or whatever port you used). If you know how to do this on your own, go for it. If you need them, instructions to do it with SequelPro below:
- Open SequelPro
- Click the '+' button in the bottom left to create a new, saveable connection
- Give it a name, e.g. TJI
- Enter the configuration parameters you see in the `docker-compose.yml` file. Right now, this looks like:
  - Host: 127.0.0.1 (you can type 'localhost' and sequelpro will change this for you to 127.0.0.1)
  - Username: tji
  - Password: (paste from docker-compose file)
  - Database: wordpress
  - Port: 3307 (or whatever you used in the docker-compose port mapping above)
- Click 'connect'
- Find the `wp_options` table
- Click the 'Content' button at the top to display the database content.
- Find two rows: `siteurl` and `home` and change their option_value to `http://localhost:8080/` (or whatever port you used)
- Be sure that the changes saved (hit enter after typing, so it doesn't leave the edited field unsaved)

8. Restart everything:
```
docker-compose down
docker-compose up
```
Open http://localhost:8080 and behold! (Note: your may have to hard-refresh your browser page, if it has cached any of the links).

9. Get ready to GitHub

You just copied the code from the server, but you want to be using the code from this repo to develop your changes. This repo represents the `/root/tji/www/html/wp-content/themes/tji/` subfolder of the full install. So you want to remove that from the files you just copied, and clone this repo instead. E.g.
```
cd root/tji/www/html/wp-content/themes/
rm -rf tji
git clone git@github.com:texas-justice-initiative/website.git tji
cd tji
```

10. Now you can git branch/merge/push/pull/twerk as usual

## WordPress File Hierarchy

WordPress is a content management system that provides the ability to easily display content by using custom or pre-built themes. Themes in WordPress use a template hierarchy to display all website content. In general, WordPress organizes content into posts and pages, which contain common or unique header and footer areas, a main content area, and sidebars with widgets. For a visual diagram of WordPress file hierarchy and naming conventions, see https://developer.wordpress.org/themes/basics/template-hierarchy/.

### Functions.php & style.css

These are the two core files which WordPress looks for in a theme. Functions.php handles core site-wide functions, including links to stylesheets and JavaScript source files, defining post types (the Visualization custom post type is defined here), registering sidebars and widget areas, and more.

Style.css is the main stylesheet used in a WordPress theme. In WordPress, the commented section at the beginning of the file is used to define the theme name, description, version number, author, etc. The theme styles are provided by secondary stylesheets, though.

## TJI Core Files

The following is an overview of the core files for the TJI website, including a description of key templates, stylesheets, and JavaScript. 

### Stylesheets

Version 1.0 of the website stylesheets are located in two areas within the theme directory. The majority of the styles have been written using the SASS preprocessor.

* [/sass/tji/](/sass/tji) contains site-wide styles. Will eventually contain all the styles.
* [/tji-data.css](/tji-data.css) for additional styles (primarily the homepage slider).

### JavaScript

All JavaScript code it stored within [/js/](/js). The following outline core JavaScript files for the TJI website.

* [tji.js](/js/tji.js) - The general JavaScript code used site-wide; covers the simple functionality.
* [tji-datasets-explore.js](/js/tji-datasets-explore.js) - Contains the JavaScript code used on the _Explore the Data_ page to create the charts.
* [tji-datasets-home.js](/js/tji-datasets-home.js) - Contains the JavaScript code for the _Homepage_ to create the charts.

### Page Templates

The following are important page templates which contain the html structure for various pages.

* [header.php](header.php) - Loaded on all site pages, contains links to outside sources (Google Fonts, etc.), site meta information, and header navigation structure.
* [footer.php](footer.php) - Loaded on all site pages, contains JavaScript code which can be loaded after page content, and footer structure. 
* [template-frontpage.php](template-frontpage.php) - The template file used to display content on the _Homepage_.
* [page-data.php](page-data.php) - The template file used to display content on the _Explore the Data_ page.
