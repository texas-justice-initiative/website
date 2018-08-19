# OMG WHY - Texas Justice Initiative: Website Theme

To learn more about TJI, visit our website at www.texasjusticeinitiative.org

## Developers/contributors -- check out the [wiki](https://github.com/texas-justice-initiative/website/wiki)

Detailed information about getting set up locally, deploying, webhooks, etc resides there.

## About this repo

This repo contains the theme files for the Texas Justice Initiative website. The website is built on the WordPress CMS using this custom theme. Below is an outline of the WordPress file structure and theme organization.

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
