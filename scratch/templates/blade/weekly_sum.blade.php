<? 
  $days = [
    'sun'=>'Sunday',
    'mon'=>'Monday',
    'tue'=>'Tuesday',
    'wed'=>'Wednesday',
    'thu'=>'Thursday',
    'fri'=>'Friday',
    'sat'=>'Saturday'
  ];
?>
<div>
  <table class="table">
    <thead>
      <tr>
        @foreach($days as $key=>$label)
        <th>{{$label}}</th>
        @endforeach
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        @foreach($days as $key=>$label)
        <td><input type="number" name="{{question->name}}_{{$key}}"></td>
        @endforeach
        <td><input type="number" name="{{question->name}}" disabled></td>
      </tr>
    </tbody>
  </table>
  <p>
    That totals 
    <strong><span id="{{question->name}}_min"></span> minutes
    or <span id="{{question->name}}_hrs"></span> hour(s)</strong> and <strong><span id="{{question->name}}_hr_min"></span> minutes</strong>. 
    Does that sound about right?
  </p>
</div>