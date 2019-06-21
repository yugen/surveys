@component('surveys::questions.question', compact('renderable', 'context'))

    @slot('answers')
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <div class="input-group-text">$</div>
            </div>
            @include('surveys::questions.input', ['type'=>'text', 'question'=>$renderable, 'context'=>$context])
        </div>
    @endslot

@endcomponent