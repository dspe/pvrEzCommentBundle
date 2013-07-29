# pvrEzCommentBundle

The ```pvrEzCommentBundle``` adds support for comments for ezpublish 5. This is an evolution from original extension
eZComments available on legacy stack.

Important note: the master of this repository is containing current development in order to work with eZ Publish 2013.6.
If you are using old version of eZ Publish, this will probably not work and will be not supported too.

## Installation

### Requirements

In order to work fully, this bundle need to have some dependencies:
* Gregwar's CaptchaBundle
* SwiftMailer Bundle

### Step 1: Download pvrEzCommentBundle

You can accomplish this several ways, depending on your personal preference.

**Using composer.json**

Add the following to the "require" section of your ```composer.json``` file.

```
    "dspe/pvrEzCommentBundle": "dev-master"
```

### Step 2: Enable the Bundle

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

### Configuration

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
        enabled:  false
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

### Usage

This first version require to enable ```YUI library``` and some css. You have to put those two lines on your project

```jinga
    '@pvrEzCommentBundle/Resources/public/js/yui/3.11.0/build/yui/yui-min.js'
```

```jinga
   '@pvrEzCommentBundle/Resources/public/css/pvrezcomment.css'
```

On your template, for example article.html.twig, just put this line:
```jinga
     {{ render( controller( "pvrEzCommentBundle:Comment:getComments", {'contentId': content.id })) }}
```

That's all :)

## Todo

Of course this bundle is not finish but already usable. We don't recommend to use it on prod environment. Some
new features should be enabled before this.

What is implemented (and not) yet:

- [x] Add anonymous comment
- [x] Add ezuser comment (using data form eZUser)
- [x] Add moderating status for comment
- [x] Send mail to administrator when a new comment should be approved
- [x] Create an control interface to approve/reject comments
- [ ] Notify visitor's part is not implemented yet
- [ ] Add more documentation on php file and create a doc folder
- [ ] Add a second security system like Akismet
- [ ] Add PhpUnit testing
- [x] Add translations (english and french at least)
- [ ] Add rss feed to comment by content


Feel free to participate :)

## License

This bundle is under the MIT license. See the complete license in the bundle: LICENSE