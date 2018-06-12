# Texas Justic Initiative: Website Theme

To learn more about TJI, visit our website at www.texasjusticeinitiative.org

## About this repo

This repo contains the theme files for the Texas Justice Initiative website. The website is built on the WordPress CMS using this custom theme. Below is an outline of the WordPress file structure and theme organization.

## WordPress File Hierarchy

WordPress is a content management system that provides the ability to easily display content by using custom or pre-built themes. Themes in WordPress use a template hierarchy to display all website content. In general WordPress organizes content into posts and pages, which contain common or unique header and footer areas, a main content area, and sidebars with widgets. For a visual diagram of WordPress file hierarchy and naming conventions, see https://developer.wordpress.org/themes/basics/template-hierarchy/.

### Functions.php & style.css

These are the two core files which WordPress looks for in a theme. Functions.php handles core site-wide functions including links to stylesheets and JavaScript source files, defining post types (in our case the Visualization custom post type is defined here), registering sidebars and widget areas, and more.

Style.css is the main stylesheet used in a WordPress theme. In WordPress, the commented section at the beginning of the file is used to define the theme name, description, version number, author, etc. The TJI website leaves this file for this purpose alone with secondary stylesheets providing the theme styles.

## TJI Core Files

The following is an overview of the core files for the TJI website, including a description of key templates, stylesheets, and JavaScript. 

### Stylesheets

Version 1.0 of the website stylesheets are located in two areas within the theme directory. The majority of the styles have been written using the SASS preprocessor.

* [/sass/tji/](https://github.com/texas-justice-initiative/website/tree/master/sass/tji) which contains site-wide styles and will eventually contain all styles.
* [/tji-data.css](https://github.com/texas-justice-initiative/website/tree/master/tji-data.css) for additional styles (primarily the homepage slider).

### JavaScript

All JavaScript code it stored within [/js/](https://github.com/texas-justice-initiative/website/tree/master/js). The following outline core JavaScript files for the TJI website.

* [tji.js](https://github.com/texas-justice-initiative/website/tree/master/js/tji.js) - General JavaScript which is used site-wide and covers simple functionality.
* [tji-datasets-explore.js](https://github.com/texas-justice-initiative/website/tree/master/js/tji-datasets-explore.js) - Contains JavaScript code used on the Explore the Data page to pull from data.world and create charts.
* [tji-datasets-home.js](https://github.com/texas-justice-initiative/website/tree/master/js/tji-datasets-home.js) - Contains JavaSCript code used on the Homepage to pull from data.world and create charts.

### Page Templates

The following are important page templates which contain the html structure for various pages.

* [header.php](https://github.com/texas-justice-initiative/website/tree/master/header.php) - Loaded on all site pages, contains links to outside sources (Google Fonts, etc.), site meta information, and header navigation structure.
* [footer.php](https://github.com/texas-justice-initiative/website/tree/master/footer.php) - Loaded on all site pages, contains JavaScript code which can be loaded after page content, and footer structure. 
* [template-frontpage.php](https://github.com/texas-justice-initiative/website/tree/master/template-frontpage.php) - The template file used to display content on the homepage.
* [page-data.php](https://github.com/texas-justice-initiative/website/tree/master/page-data.php) - The template file used to display content on the Explore the Data page.
