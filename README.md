# Itoop.Facebook
Nodetype to integrate a fully customized facebook postlist using the facebook graph-sdk.

## What it provides
Easily adding a facebook postlist in Neos CMS. 

# Install
Install via composer as a dev package
```bash
php composer.phar require facebook/graph-sdk
php composer.phar require "itoop/facebook" "dev-master"
```
# Usage
Generate app-ID, app-Secret and token and put these values in the inspector.

Add ```bash <link rel="stylesheet" href="{f:uri.resource(path: 'Styles/Facebook.css', package:'Itoop.Facebook')}" media="all" /> ``` to the stylesheets-section of your page template.

Customize template and css.
