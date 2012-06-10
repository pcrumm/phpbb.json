# phpbb.json
### JSON API for phpBB forums

## What is phpbb.json?
phpbb.json is a JSON API for phpBB forums. It is designed to operate independently of the phpBB forum, but uses some phpBB components (dbal) for easy of use.

## What can I do with it?
Whatever you'd like. You can install phpbb.json on your forum and allow users to easily create applications using your forum's data. One potential use would be a mobile phone application for phpBB forums (like Tapatalk, perhaps, but focused on phpBB forums to provide a better user experience).

## Is my data safe?
phpbb.json utilizes phpBB's permissions to determine what data is exposed. Users will have access to no more information than they would if they browsed your board via the standard interface.

## Development Information
### Who's behind this?
This project was started by [Phil Crumm](http://github.com/pcrumm) (Phil on the phpBB community) in response to [this topic](http://www.phpbb.com/community/viewtopic.php?f=6&t=2156025). See also the [development topic on phpBB.com](http://www.phpbb.com/community/viewtopic.php?f=70&t=2157397).

### Who can contribute?
Anyone! This project is hosted on Github and licensed under GPLv2. I welcome pull requests for features, bugs, documentation, or anything else you care to contribute. If you'd like to be involved as a long-term contributor, please contact me.

### What's this about testing?
All contributed features must be testable via [phpUnit](http://phpunit.de/). Bug reports with failing tests are equally encouraged :)

## Features
### Planned Features
This is a preliminary list of planned features, and is subject to change.

* Authentication, with auth plugin support
* Sessions
* Forum listing
* Topic posting/reply
* Topic actions (quote, report)
* Topic moderation
* Forum moderation
* Private messaging
* Push notifications?

## Installation
This is development software and is not feature-complete nor ready for public consumption. To install on a **testing environment**, just drop the api/ folder into your board root.

A script that sets up a development environment and runs some automated tests will be coming soon.