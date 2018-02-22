<div class="modal fade" tabindex="-1" role="dialog" id="revision-history-modal">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{{$response->survey->name}} - Revision History</h4>
        </div>
        <div class="modal-body">
            <div id="revision-history">
                <?php 
                    $revisions = $response->revisionHistory->groupBy(function($item, $key){
                        return $item->created_at->format('Y-m-d H:i:s');
                    });
                ?>
                <div class="row">
                    <div class="col-sm-2">Date/Time</div>
                    <div class="col-sm-7">Changes</div>
                    <div class="col-sm-3">User</div>
                </div>
                @foreach($revisions as $key => $revision)
                <div class="row separator-top revision-row">
                    <div class="col-sm-2">
                        <strong>{{ $revision->first()->created_at->format('Y-m-d') }}</strong><br>
                        {{ $revision->first()->created_at->format("g:ia") }}
                    </div>
                    <div class="col-sm-7">
                        @foreach($revision as $field)
                            <div class="row separator-bottom-narrow data-row">
                                <div class="col-sm-4"><strong>{{$field->fieldName()}}:</strong></div>
                                <div class="col-sm-8">
                                    <s class="text-muted">{{$field->oldValue()}}</s>
                                    <span>{{$field->newValue()}}</span>
                                </div>
                            </div>
                        @endforeach
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <strong>{{$revision->first()->userResponsible()->full_name}}</strong>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@push('styles')
    <style>
        .revision-row.highlight,
        .revision-row .data-row.highlight{
            background-color: rgba(91,192,222,.2);
        }
    </style>
@endpush

@push('scripts')
<script>
    $(document).ready(function(){
        $('.revision-row, .revision-row .data-row').hover(function(){
            $(this).addClass('highlight');
        }, function(){
            $(this).removeClass('highlight');
        })
    });
</script>
@endpush