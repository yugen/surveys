# Sirs Surveys #

A package for building surveys using xml and rendering them to html in Laravel projects.

### Installing this package ###

Add the following to your composer.json: 
```
"repositories": [
    {
      "type": "vcs",
      "url": "ssh://hg@bitbucket.org/shepsweb/sirs-surveys"
    }
]
...
"require": {
    ...
    "sirs/surveys": "dev-default",  
    ...
}
```
Add the service provider to config/app.php: 
```
Sirs\Surveys\SurveysServiceProvider::class,
```

add the service provider to your app config:
```
Sirs\Surveys\SurveysServiceProvider::class,
```

Publish the stylesheets and config file
```
$ php artisan vendor:publish
```

### Quick Start Guide ###
1. Configure: update /config/surveys.php
2. Create Survey definition directory: /resources/surveys
3. Create custom template directory: /resources/views/surveys
4. write your first survey and save in /resources/surveys
** See survey definition schema docs in the wiki
5. Run ```php artisan survey:new <path_to_survey>``` to create a migration and rules file
6. To replace the migration run ```php artisan survey:migration <path_to_survey>```

### [Full Package Documentation](https://bitbucket.org/shepsweb/sirs-surveys/wiki/) ###


### Who do I talk to? ###

* TJ Ward - jward3@email.unc.edu
* Alex Harding - ahhardin@email.unc.edu