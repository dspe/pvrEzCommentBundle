### pvrEzCommentBundle


The ```pvrEzCommentBundle``` adds support for comments for ezpublish 5. This is an evolution from original extension
eZComments available on legacy stack.

Important note: the master of this repository is containing current development in order to work with eZ Publish 2014.6.
If you are using old version of eZ Publish, this will probably not work and will be not supported too.

### Installation

## Requirements

In order to work fully, this bundle need to have some dependencies:
* Gregwar's CaptchaBundle
* SwiftMailer Bundle

## Step 1: Download pvrEzCommentBundle

You can accomplish this several ways, depending on your personal preference.

** Using composer.json **

Add the following to the "require" section of your ```composer.json``` file.

```
    "dspe/pvrEzCommentBundle": "dev-master"
```

## Step 2: Enable the Bundle

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

## Configuration

We recommend to add this routing to your ```routing.yml``` file

```
pvr_ezcomment_routing:
    resource: "@pvrEzCommentBundle/Resources/config/routing.yml"
```

You have to add configuration for CaptchaBundle. Put this line to your ```config.yml```

```yaml
gregwar_captcha: ~
```

```pvrEzCommentBundle``` don't need configuration. It could works without configuration. But if you need
to customize it, this is a full configuration list.

```yaml
pvr_ez_comment:
    anonymous:    false
    moderating:   false
    moderate_mail:
        subject:  "Notify mail"
        from:     "no-reply@example.com"
        to:       "me@example.com"
        template: "pvrEzCommentBundle:mail:email_moderate.txt.twig"
    notify_mail:
        subject:  "Notify mail"
        from:     "noreply@example.com"
        template: "pvrEzCommentbundle:mail:email.txt.twig"
```

**Description**

```anonymous```: set to true if you would like to use anonymous comment. This will use captcha to prevent spam.
```moderating```: if you would like to moderate content before publishing it. You have to fill correctly *moderate_mail*
settings and *notify_mail* settings.
```moderate_mail```: when *moderating* is set to true, an user will receive an email each time a comment should be moderate.
You could define a new *subject*, *template* and so on.
```notify_mail```: when a comment is approved, a mail could be sent to visitor to notice it.

