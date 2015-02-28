# Getting Started with pvrEzCommentBundle

## Installation

 1. [Install dependencies](1-install_dependencies.md)
 2. [Setting up the bundle](2-settings_up_the_bundle.md)
 3. [Importing pvrEzCommentBundle routing](3-importing_routing.md)
 4. [Enable comments on a page](4-enabling_comments.md)

## Optional next steps

 * Style it

## Todo

Of course this bundle is not finish but already usable. We don't recommend to use it on prod environment. Some
new features should be enabled before this.

What is implemented (and not) yet:

 - [x] Add anonymous comment
 - [x] Add ezuser comment (using data form eZUser)
 - [x] Add moderating status for comment
 - [x] Send mail to administrator when a new comment should be approved
 - [x] Create an control interface to approve/reject comments
 - [x] ESI support for production env.
 - [x] Add translations (english and french at least)
 - [ ] Notify visitor's part is not implemented yet
 - [x] Add more documentation on php file and create a doc folder
 - [ ] Add a second security system like Akismet
 - [ ] Add PhpUnit testing
 - [ ] Add rss feed to comment by content
 - [ ] Rating comments
 - [ ] Add link to report comment
 - [x] Add a sort system
 - [x] Support eZ 5.4 and Symfony >= 2.6


Feel free to participate :)