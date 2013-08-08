# Step 1: Install dependencies

In order to work fully, this bundle need to have some dependencies:
* [eZ Commment (Legacy)](https://github.com/ezsystems/ezcomments/tree/master/packages/ezcomments_extension/ezextension/ezcomments)
* [Gregwar's CaptchaBundle](https://github.com/Gregwar/CaptchaBundle)
* SwiftMailer Bundle

## a) Install eZ Comment on Legacy Kernel

For this step, please read full [installation documentation](https://github.com/ezsystems/ezcomments/blob/master/packages/ezcomments_extension/ezextension/ezcomments/doc/INSTALL)

## b) Configure your users roles

If you would like that ezuser could post some comment, you have to add this policies to your role: ```comment/add```


### Continue to the next step !

When you're done. Continue by setting up the bundle: [Step 2: Setting up the bundle](2-settings_up_the_bundle.md)