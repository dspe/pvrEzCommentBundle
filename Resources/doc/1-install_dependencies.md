# Step 1: Install dependencies

In order to work fully, this bundle need to have some dependencies:
* [Gregwar's CaptchaBundle](https://github.com/Gregwar/CaptchaBundle)
* SwiftMailer Bundle

## a) Install Pvr eZ Comment

### Via composer

If you use eZ Platform >=1.6 or eZ Studio > 1.6 
```
php composer.phar require --prefer-dist dspe/pvrezcommentbundle:~2.0
```

## b) Install SQL schema (optional if not an upgrade)

You need to run this command line to install SQL Schema:

```
php app/console ezplatform:install comment
```

## c) Configure your users roles

If you would like that ezuser could post some comment, you have to add this policies to your role: ```comment/add```


### Continue to the next step !

When you're done. Continue by setting up the bundle: [Step 2: Setting up the bundle](2-settings_up_the_bundle.md)

