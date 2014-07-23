# Step 2: Setting up the bundle

## a) Install pvrEzCommentBundle

You can accomplish this several ways, depending on your personal preference.

**Using composer.json**

Add the following to the "require" section of your ```composer.json``` file.

```
    "dspe/pvrEzCommentBundle": "dev-master"
```

## b) Enable the Bundle

Enable the bundle in the EzpublishKernel:

```php
<?php
// ezpublish/EzpublishKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new pvr\EzCommentBundle\pvrEzCommentBundle(),
    );
}
```

## c) Register parameter: secret

In your ```parameters.yml``` file or wherever you consider proper you need to set:

```
    secret: YOUR_SECRET
```
(we recommend not to user framework.secret or kernel.secret as the encrypt algorithm does not support their length)
    

### Continue to the next step !

When you're done, continue by importing the routing: [Step 3: Importing pvrEzCommentBundle routing](3-importing_routing.md)