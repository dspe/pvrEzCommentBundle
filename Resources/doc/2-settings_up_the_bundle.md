
# Step 2: Setting up the bundle

## a) Enable the Bundle

Enable the bundle in the EzpublishKernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
        new pvr\EzCommentBundle\PvrEzCommentBundle(),
    );
}
```

## b) Update your config.yml

In order to use stylesheets provide by the bundle, you have to update:

```yaml
assetic:
    bundles:        [ PvrEzCommentBundle ]
```

and add configuration for captcha:

```yaml
gregwar_captcha: ~
```

## c) Register parameter: secret

In your ```parameters.yml``` file or wherever you consider proper you need to set:

```
    secret: YOUR_SECRET
```
(we recommend not to user framework.secret or kernel.secret as the encrypt algorithm does not support their length)
    

### Continue to the next step !

When you're done, continue by importing the routing: [Step 3: Importing pvrEzCommentBundle routing](3-importing_routing.md)
