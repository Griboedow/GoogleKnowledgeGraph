Google Knowledge Graph extension adds short descriptions for specified terms.

# Warning
The extension is provided only for education purpose. 
Using it in production is not recommended: Google provides only 100 000 requests for free. 

# Installation and configuration
To install the extension, place the folder in ./extensions and add in LocalSettings.php:

```php
wfLoadExtension( 'GoogleKnowledgeGraph' );
$wgApiToken = 'your-google-token';
$wgApiLanguage = 'en';
```

# Where to get Google API token
You can get token on:
https://console.cloud.google.com/flows/enableapi?apiid=kgsearch.googleapis.com&credential=client_key

# Usage
To use the extension add on a page tag:

```html
<GoogleKnowledgeGraph query="Mario">
```
and save the page

You'll get something like that:
![GKG_sample](https://user-images.githubusercontent.com/4194526/111924847-ad33f600-8aa6-11eb-9eee-303169491641.jpg)
