@extends('omega::master')

@section('page_header')
    <div class="page-title">
        <i class="omega-data"></i> @if(isset($dataType->id)){{ 'Edit BREAD for ' . $dataType->name . ' table' }}@elseif(isset($table)){{ 'Create BREAD for ' . $table . ' table' }}@endif
    </div>
@stop

@section('content')

    @if(isset($dataType->name))
        <?php $table = $dataType->name; ?>
    @endif

    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <form action="@if(isset($dataType->id)){{ route('omega.database.update_bread', $dataType->id) }}@else{{ route('omega.database.store_bread') }}@endif"
                      method="POST" role="form">
                @if(isset($dataType->id))
                    <input type="hidden" value="{{ $dataType->id }}" name="id">
                    {{ method_field("PUT") }}
                @endif
                    <!-- CSRF TOKEN -->
                    {{ csrf_field() }}

                    <div class="panel panel-primary panel-bordered">

                        <div class="panel-heading">
                            <h3 class="panel-title">Edit the rows for the {{ $table }} table below:</h3>
                        </div>

                        <table id="users" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Field Info</th>
                                    <th>Visibility</th>
                                    <th>Input Type</th>
                                    <th>Display Name</th>
                                    <th>Optional Details</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($fieldOptions as $data)

                                @if(isset($dataType->id))
                                    <?php $dataRow = TCG\Omega\Models\DataRow::where('data_type_id', '=',
                                            $dataType->id)->where('field', '=', $data['field'])->first(); ?>
                                @endif
                                <tr>
                                    <td><h4><strong>{{ $data['field'] }}</strong></h4></td>
                                    <td>
                                        <strong>Type:</strong> <span>{{ $data['type'] }}</span><br/>
                                        <strong>Key:</strong> <span>{{ $data['key'] }}</span><br/>
                                        <strong>Required:</strong>
                                        @if($data['null'] == "NO")
                                            <span>Yes</span>
                                            <input type="hidden" value="1" name="field_required_{{ $data['field'] }}"
                                                   checked="checked">
                                        @else
                                            <span>No</span>
                                            <input type="hidden" value="0" name="field_required_{{ $data['field'] }}">
                                        @endif
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                               id="field_browse_{{ $data['field'] }}"
                                               name="field_browse_{{ $data['field'] }}"
                                               @if(isset($dataRow->browse) && $dataRow->browse)
                                                    {{ 'checked="checked"' }}
                                               @elseif($data['key'] == 'PRI')
                                               @elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')
                                               @elseif(!isset($dataRow->browse))
                                                    {{ 'checked="checked"' }}
                                               @endif>
                                        <label for="field_browse_{{ $data['field'] }}">Browse</label><br/>
                                        <input type="checkbox"
                                               id="field_read_{{ $data['field'] }}"
                                               name="field_read_{{ $data['field'] }}" @if(isset($dataRow->read) && $dataRow->read){{ 'checked="checked"' }}@elseif($data['key'] == 'PRI')@elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')@elseif(!isset($dataRow->read)){{ 'checked="checked"' }}@endif>
                                        <label for="field_read_{{ $data['field'] }}">Read</label><br/>
                                        <input type="checkbox"
                                               id="field_edit_{{ $data['field'] }}"
                                               name="field_edit_{{ $data['field'] }}" @if(isset($dataRow->edit) && $dataRow->edit){{ 'checked="checked"' }}@elseif($data['key'] == 'PRI')@elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')@elseif(!isset($dataRow->edit)){{ 'checked="checked"' }}@endif>
                                        <label for="field_edit_{{ $data['field'] }}">Edit</label><br/>
                                        <input type="checkbox"
                                               id="field_add_{{ $data['field'] }}"
                                               name="field_add_{{ $data['field'] }}" @if(isset($dataRow->add) && $dataRow->add){{ 'checked="checked"' }}@elseif($data['key'] == 'PRI')@elseif($data['type'] == 'timestamp' && $data['field'] == 'created_at')@elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')@elseif(!isset($dataRow->add)){{ 'checked="checked"' }}@endif>
                                            <label for="field_add_{{ $data['field'] }}">Add</label><br/>
                                        <input type="checkbox"
                                               id="field_delete_{{ $data['field'] }}"
                                               name="field_delete_{{ $data['field'] }}" @if(isset($dataRow->delete) && $dataRow->delete){{ 'checked="checked"' }}@elseif($data['key'] == 'PRI')@elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')@elseif(!isset($dataRow->delete)){{ 'checked="checked"' }}@endif>
                                                <label for="field_delete_{{ $data['field'] }}">Delete</label><br/>
                                    </td>
                                    <input type="hidden" name="field_{{ $data['field'] }}" value="{{ $data['field'] }}">
                                    <td>
                                        @if($data['type'] == 'timestamp')
                                            <p>Timestamp</p>
                                            <input type="hidden" value="timestamp"
                                                   name="field_input_type_{{ $data['field'] }}">
                                        @else
                                            <select name="field_input_type_{{ $data['field'] }}">
                                                @foreach (Omega::formFields() as $formField)
                                                    <option value="{{ $formField->getCodename() }}" @if(isset($dataRow->type) && $dataRow->type == $formField->getCodename()){{ 'selected' }}@endif>
                                                        {{ $formField->getName() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif

                                    </td>
                                    <td><input type="text" class="form-control"
                                               value="@if(isset($dataRow->display_name)){{ $dataRow->display_name }}@else{{ ucwords(str_replace('_', ' ', $data['field'])) }}@endif"
                                               name="field_display_name_{{ $data['field'] }}"></td>
                                    <td>
                                        <div class="alert alert-danger validation-error">
                                            Invalid JSON
                                        </div>
                                        <textarea id="json-input-{{ $data['field'] }}" class="resizable-editor" data-editor="json" name="field_details_{{ $data['field'] }}">@if(isset($dataRow->details)){{ $dataRow->details }}@endif</textarea>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>

                    </div><!-- .panel -->

                    <div class="panel panel-primary panel-bordered">

                        <div class="panel-heading">
                            <h3 class="panel-title">{{ ucfirst($table) }} BREAD info</h3>
                        </div>

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="name">Table Name</label>
                                <input type="text" class="form-control" readonly name="name" placeholder="Name"
                                       value="@if(isset($dataType->name)){{ $dataType->name }}@else{{ $table }}@endif">
                            </div>
                            <div class="form-group">
                                <label for="email">URL Slug (must be unique)</label>
                                <input type="text" class="form-control" name="slug" placeholder="URL slug (ex. posts)"
                                       value="@if(isset($dataType->slug)){{ $dataType->slug }}@else{{ $slug }}@endif">
                            </div>
                            <div class="form-group">
                                <label for="email">Display Name (Singular)</label>
                                <input type="text" class="form-control" name="display_name_singular"
                                       placeholder="Display Name (Singular)"
                                       value="@if(isset($dataType->display_name_singular)){{ $dataType->display_name_singular }}@else{{ $display_name }}@endif">
                            </div>
                            <div class="form-group">
                                <label for="email">Display Name (Plural)</label>
                                <input type="text" class="form-control" name="display_name_plural"
                                       placeholder="Display Name (Plural)"
                                       value="@if(isset($dataType->display_name_plural)){{ $dataType->display_name_plural }}@else{{ $display_name_plural }}@endif">
                            </div>
                            <div class="form-group">
                                <label for="email">Icon (optional) Use a <a
                                            href="{{ config('omega.assets_path') . '/fonts/omega/icons-reference.html' }}"
                                            target="_blank">Omega Font Class</a></label>
                                <input type="text" class="form-control" name="icon"
                                       placeholder="Icon to use for this Table"
                                       value="@if(isset($dataType->icon)){{ $dataType->icon }}@endif">
                            </div>
                            <div class="form-group">
                                <label for="email">Model Name (ex. \App\User, if left empty will try and use the table
                                    name)</label>
                                <input type="text" class="form-control" name="model_name" placeholder="Model Class Name"
                                       value="@if(isset($dataType->model_name)){{ $dataType->model_name }}@else{{ $model_name }}@endif">
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6 form-group">
                                    <label for="generate_permissions">Generate Permissions</label><br>
                                    <?php $checked = (isset($dataType->generate_permissions) && $dataType->generate_permissions == 1) ? true : (isset($generate_permissions) && $generate_permissions) ? true : false; ?>
                                    <input type="checkbox" name="generate_permissions" class="toggleswitch"
                                           @if($checked) checked @endif>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="server_side">Server-side Pagination</label><br>
                                    <?php $checked = (isset($dataType->server_side) && $dataType->server_side == 1) ? true : (isset($server_side) && $server_side) ? true : false; ?>
                                    <input type="checkbox" name="server_side" class="toggleswitch"
                                           @if($checked) checked @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description"
                                          placeholder="Description">@if(isset($dataType->description)){{ $dataType->description }}@endif</textarea>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div><!-- .panel-body -->

                    </div><!-- .panel -->
                </form>
            </div><!-- .col-md-12 -->
        </div><!-- .row -->
    </div><!-- .page-content -->



@stop

@section('javascript')
    <script src="{{ config('omega.assets_path') }}/lib/js/ace/ace.js"></script>
    <script>
        window.invalidEditors = [];
        var validationAlerts = $('.validation-error');
        validationAlerts.hide();
        $(function () {
            $('textarea[data-editor]').each(function () {
                var textarea = $(this),
                mode = textarea.data('editor'),
                editDiv = $('<div>', {
                    position: 'absolute',
                    width: 250,
                    resize: 'vertical',
                    class: textarea.attr('class')
                }).insertBefore(textarea),
                editor = ace.edit(editDiv[0]),
                _session = editor.getSession(),
                valid = false;
                textarea.hide();

                // Validate JSON
                _session.on("changeAnnotation", function(){
                    valid = _session.getAnnotations().length ? false : true;
                    if (!valid) {
                        if (window.invalidEditors.indexOf(textarea.attr('id')) < 0) {
                            window.invalidEditors.push(textarea.attr('id'));
                        }
                    } else {
                        for(var i = window.invalidEditors.length - 1; i >= 0; i--) {
                            if(window.invalidEditors[i] == textarea.attr('id')) {
                               window.invalidEditors.splice(i, 1);
                            }
                        }
                    }
                });

                // Use workers only when needed
                editor.on('focus', function () {
                    _session.setUseWorker(true);
                });
                editor.on('blur', function () {
                    if (valid) {
                        textarea.siblings('.validation-error').hide();
                        _session.setUseWorker(false);
                    } else {
                        textarea.siblings('.validation-error').show();
                    }
                });

                _session.setUseWorker(false);

                editor.setAutoScrollEditorIntoView(true);
                editor.$blockScrolling = Infinity;
                editor.setOption("maxLines", 30);
                editor.setOption("minLines", 4);
                editor.setOption("showLineNumbers", false);
                editor.setTheme("ace/theme/github");
                _session.setMode("ace/mode/json");
                if (textarea.val()) {
                    _session.setValue(JSON.stringify(JSON.parse(textarea.val()), null, 4));  
                }
                
                _session.setMode("ace/mode/" + mode);
                
                // copy back to textarea on form submit...
                textarea.closest('form').on('submit', function (ev) {
                    if (window.invalidEditors.length) {
                        ev.preventDefault();
                        ev.stopPropagation();
                        validationAlerts.hide();
                        for (var i = window.invalidEditors.length - 1; i >= 0; i--) {
                            $('#'+window.invalidEditors[i]).siblings('.validation-error').show();
                        }
                        toastr.error('Seems like you introduced some invalid JSON.', 'Validation errors', {"preventDuplicates": true, "preventOpenDuplicates": true});
                    } else {
                        if (_session.getValue()) {
                            // uglify JSON object and update textarea for submit purposes
                            textarea.val(JSON.stringify(JSON.parse(_session.getValue())));
                        }
                    }
                });
            });

            $('.toggleswitch').bootstrapToggle();
        });
    </script>
@stop
