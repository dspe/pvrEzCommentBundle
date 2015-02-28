# Step 3: Importing pvrEzCommentBundle routing

We recommend to add this routing to your ```routing.yml``` file

```
pvr_ezcomment_routing:
    resource: "@PvrEzCommentBundle/Resources/config/routing.yml"
```

You have to add configuration for CaptchaBundle. Put this line to your ```config.yml```

```yaml
gregwar_captcha: ~
```

```PvrEzCommentBundle``` don't need configuration. It could works without configuration. But if you need
to customize it, this is a full configuration list.

```yaml
pvr_ez_comment:
    anonymous:    false
    moderating:   false
    moderate_mail:
        subject:  "Notify mail"
        from:     "no-reply@example.com"
        to:       "me@example.com"
        template: "PvrEzCommentBundle:mail:email_moderate.txt.twig"
    notify_mail:
        enabled:  false
        subject:  "Notify mail"
        from:     "noreply@example.com"
        template: "PvrEzCommentbundle:mail:email.txt.twig"
```

**Description**

```anonymous```: set to true if you would like to use anonymous comment. This will use captcha to prevent spam.

```moderating```: if you would like to moderate content before publishing it. You have to fill correctly *moderate_mail*
settings and *notify_mail* settings.

```moderate_mail```: when *moderating* is set to true, an user will receive an email each time a comment should be moderate.
You could define a new *subject*, *template* and so on.

```notify_mail```: when a comment is approved, a mail could be sent to visitor to notice it.


**Note**
Be careful if you're not using anonymous mode, you have to configure policies for users: they have to access to comment/add


### Continue to the next step !

When you're done, continue by enabling comments: [Step 4: Enabling comments](4-enabling_comments.md)
