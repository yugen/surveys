<options>
  <option value="1">Afam</option>
  <option value="2">Aisian</option>
  ...
  <option value="n">White</option>
</options>

<options>
  <data-source lang="php">
    <class>App\Race</class>
    <method>getOptions</method>
  </data-source>
</options>

<options>
  <remote-data-source lang="json">
    <uri>change.sirs.unc.edu/api/race</class>
    <method>get</method>
  </data-source>
</options>

<?php
  <?
    $options = call_user_method_array('getOptions', App\Race::class);
  ?>
  @foreach($options as $opt)
    <label>
      <input type="radio" name="name" id="name_{{$opt->id}}" value="{{$opt->id}}" />
      {{$opt->name}}
    </label>
  @endforeach
?>

<label ng-repeat="opt in options">
  <input type="radio" name="name" id="name_{{opt.id}}" value="{{opt.id}}" />
  {{opt.name}}
</label>c