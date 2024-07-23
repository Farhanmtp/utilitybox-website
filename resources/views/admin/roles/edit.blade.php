@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('admin.roles.update',$role->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><h3 class="card-title">{{ $role->name }}</h3></div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.roles.index') }}"><i class="fa fa-angle-left"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="id" value="{{ $role->id }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ data_get($role,'title') }}" placeholder="Enter title">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Name</label>
                            <p class="form-control">{{ data_get($role,'name') }}</p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h4>Role Permissions:
                            @if($role->name == 'super-admin')
                                <small>{{ $role->title }} has full rights.</small>
                            @endif
                        </h4>
                        <div style="position: relative">
                            @if($role->name != 'super-admin')
                                <table class="table table-bordered" style="position: relative">
                                    <tbody>
                                    <tr>
                                        <th>{{ __('Section') }}</th>
                                        <th>{{ __('Areas') }}</th>
                                    </tr>
                                        <?php
                                        $_permissions = $role->permissions->pluck('id', 'id')->toArray();
                                        ?>
                                    @foreach($permissions->groupBy('module') as $key=> $areas)
                                            <?php
                                            $selected_tasks = $areas->whereIn('id', $_permissions)->count();
                                            ?>
                                        <tr data-id="perm_{{$key}}">
                                            <td>
                                                <label><input type="checkbox" {{ $selected_tasks == $areas->count() ? ' checked' : '' }} class="section" id="perm_{{$key}}"/> {{ __(Str::title(str_replace('-',' ',$key))) }}</label>
                                            </td>
                                            <td class="tasks">
                                                @foreach($areas as $filed)
                                                    <label> <input type="checkbox" class="task" name="permissions[]" {{ in_array($filed->id,$_permissions) ? 'checked' :'' }}  data-task="{{ $filed->task }}" value="{{ $filed->id }}"> {{ __(ucfirst($filed->task)) }}&nbsp;&nbsp;</label>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a class="btn btn-danger" href="{{ route('admin.roles.index') }}">Cancel</a>
                @if(hasPermission('roles.edit'))
                    <button type="submit" class="btn btn-success">Update</button>
                @endif
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            /*$('#roleForm').validate({
                rules: {
                    title: {
                        required: true
                    },
                    'permissions[]': {
                        required: true
                    }
                },
                messages: {
                    'permissions[]': {
                        required: "Please select at-least one permission"
                    }
                }
            });*/
            $(".section").change(function () {
                var id = $(this).attr('id');
                $(this).closest('tr[data-id="' + id + '"]').find('.tasks input').prop('checked', $(this).prop("checked"))
                //$('#roleForm').valid();
            });

            $(".tasks input").on('click', function () {
                var _td = $(this).closest('td');
                var _section = $(this).closest('tr').find('input.section');
                var _total = _td.find('input').length;
                var _checked = _td.find('input:checked').length;
                if (_td.find('[data-task="view"]').length && _checked && !_td.find('[data-task="view"]').is(':checked')) {
                    _td.find('[data-task="view"]').prop('checked', true);
                    _checked++;
                }
                console.log(_checked, _total);
                _section.prop('checked', (_checked == _total));
                //$('#roleForm').valid();
            });
        })
    </script>
@endsection