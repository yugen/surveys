# Sirs Surveys #

A package for building surveys using xml and rendering them to html in Laravel projects.

### Installing this package ###

1. Add the following to your composer.json: 
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
2. Add the service provider to config/app.php: 
```
Sirs\Surveys\SurveysServiceProvider::class,
```

3. add the service provider to your app config:
```
  Sirs\Surveys\SurveysServiceProvider::class,
```

4. Publish the stylesheets and config file
```
$ php artisan vendor:publish
```

### Quick Start Guide ###
# Configure: update /config/surveys.php
# Create Survey definition directory: /resources/surveys
# Create custom template directory: /resources/views/surveys
# write your first survey and save in /resources/surveys
#* See survey definition schema docs in the wiki
# run ```php artisan survey:new <path_to_survey>```

### Contribution guidelines ###

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* TJ Ward - jward3@email.unc.edu
* Alex Harding - ahhardin@email.unc.edu