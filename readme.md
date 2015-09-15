# Auth via GitHub

This PHP app requests students to authorize WDI applications and displays the information we retrieve about them.

- index.php: root path
- callback.php: configured as callback url, performs final step of auth, displays retrieved info.
- ghapi.php: helper file.  Connects to GitHub Auth API.
- is_created.php: Checks that student:
  - has authorized wdi,
  - exists, in "http://api.wdidc.org/students.json"
  - has an avatar


## Setup

- Ensure an github application is created (https://github.com/settings/applications), using callback.php as "Authorization callback URL".
  - locally, this is "localhost/callback.php"
- Copy config.php.example to config.php
- Update config.php using values from the github application
