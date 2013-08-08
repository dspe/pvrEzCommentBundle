# Step 4: Enable comments

## Usage

This first version require to enable ```YUI library``` and some css. You have to put those two lines on your project

```jinja
'@pvrEzCommentBundle/Resources/public/js/yui/3.11.0/build/yui/yui-min.js'
```

```jinja
'@pvrEzCommentBundle/Resources/public/css/pvrezcomment.css'
```

On your template, for example article.html.twig, just put this line:
```jinja
{{ render_esi( controller( "pvrEzCommentBundle:Comment:getComments", {'contentId': content.id, 'locationId': location.id })) }}
```

That's all :)
