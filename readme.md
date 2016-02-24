# Personal dashboard and data collecting.

[![Build Status](https://travis-ci.org/Bogstag/bogstag.se.svg)](https://travis-ci.org/Bogstag/bogstag.se)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fd0209c5-cd30-43f4-ae9b-dd72790cdbb4/mini.png)](https://insight.sensiolabs.com/projects/fd0209c5-cd30-43f4-ae9b-dd72790cdbb4)
[![Dependency Status](https://www.versioneye.com/user/projects/56c134f318b271003b391391/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56c134f318b271003b391391)
[![codecov.io](https://codecov.io/github/Bogstag/bogstag.se/coverage.svg?branch=master)](https://codecov.io/github/Bogstag/bogstag.se?branch=master)
[![StyleCI](https://styleci.io/repos/42884250/shield)](https://styleci.io/repos/42884250)

This read me is more so i can remember stuff. This project if for me to learn, but there is nothing that is unique for me so if you would like to fork, use it as you own or help me i be glad.

## Integrations
### Steam API
Integrates to steam api to pull games i own and to update games im playing and new games. The external api counter is is only counting, so there is nothing to stop you from doing too many calls to api. When using this in local environment it only calls the ap one time and cache it under storage/app/SteamApi.

#### First time setup
If you dont own a lot of games, you can load everything from a artisan command:
```
php artisan steamapi:game load
```
After that the scheduled task take care of the rest.