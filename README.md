# Itoop.Facebook
Nodetype to integrate a fully customizable facebook postlist using the facebook graph-sdk.

## What it provides
Easily adding a facebook postlist in Neos CMS. 

# Install
Install via composer as a dev package
```bash
php composer.phar require facebook/graph-sdk
php composer.phar require "itoop/facebook" "dev-master"
```

Add ```bash <link rel="stylesheet" href="{f:uri.resource(path: 'Styles/Facebook.css', package:'Itoop.Facebook')}" media="all" /> ``` to the stylesheets-section of your page template.

# Usage
Add Plugin "Facebook". Generate app-ID, app-Secret and token and put these values in the inspector.

Customize template and css if needed.

# Screenshot
![Screenshot](/Documentation/Screenshot.png?raw=true "Screenshot")
