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
```Sirs\Surveys\SurveysServiceProvider::class,```



### Contribution guidelines ###

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* TJ Ward - jward3@email.unc.edu
* Alex Harding - ahhardin@email.unc.edu