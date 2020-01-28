<div class="modal fade" tabindex="-1" role="dialog" id="revision-history-modal">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{$response->survey->name}} - Revision History</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div id="revision-history">
                <?php 
                    $revisions = $response->revisionHistory->groupBy(function ($item, $key) {
                        return $item->created_at->format('Y-m-d H:i:s');
                    });
                ?>
                <table class="table table-sm table-hover table-striped">
                    <tr>
                        <th style="width: 7rem">Date/Time</th>
                        <th>Changes</th>
                        <th style="width: 7rem">User</th>
                    </tr>
                    @foreach($revisions as $key => $revision)
                        <tr class="revision-row">
                            <td>
                                <strong>{{ $revision->first()->created_at->format('Y-m-d') }}</strong><br>
                                {{ $revision->first()->created_at->format("g:ia") }}
                            </td>
                            <td>
                                <table class="table table-sm table-borderless">
                                    @foreach($revision as $field)
                                        <tr>
                                            <td style="border-top: none; width: 7rem;"><strong>{{$field->fieldName()}}:</strong></td>
                                            <td style="border-top: none;">
                                                <span class="text-muted">{{$field->oldValue()}}</span>
                                                <span>{{$field->newValue()}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                                </ul>
                            </td>
                            <td>
                                @if($revision->first()->userResponsible())
                                    <strong>{{  $revision->first()->userResponsible()->full_name }}</strong>
                                @else
                                    <span class="text-muted">guest</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
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