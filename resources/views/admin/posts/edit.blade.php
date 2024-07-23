@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('admin.posts.update',$post->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><h3 class="card-title">{{ $post->title }}</h3></div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.posts.index') }}"><i class="fa fa-angle-left"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="id" value="{{ $post->id }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ data_get($post,'title') }}" placeholder="Enter title">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ data_get($post,'slug') }}" placeholder="Enter slug">
                        </div>
                        {{--<div class="form-group">
                            <input type="hidden" name="status" value="0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" value="1" {{ data_get($post,'status')?'checked' :'' }} id="status">
                                <label class="form-check-label" for="status">
                                    Active
                                </label>
                            </div>
                        </div>--}}
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Category</label>
                            <select name="category_id" id="category_id" class="form-control" data-live-search="true">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-content="{{ $category->title }}"
                                        {{$category->id == $post->category_id? 'selected' : '' }}>{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control"
                                      placeholder="Enter description">{{ data_get($post,'description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Content</label>
                            <textarea name="content" id="content" rows="3"
                                      class="form-control tinymce">{{ data_get($post,'content') }}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="avatar">Image</label>
                            <input type="text" name="image" id="image" class="form-control filemanager"
                                   value="{{ data_get($post,'image') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="avatar">Banner</label>
                            <input type="text" name="banner" id="banner" class="form-control filemanager"
                                   value="{{ data_get($post,'banner') }}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                   value="{{ data_get($post,'meta_title') }}" placeholder="Enter meta title">
                        </div>
                        <div class="form-group">
                            <label for="name">Meta Keyword</label>
                            <input type="text" name="meta_keyword" id="meta_keyword" class="form-control"
                                   value="{{ data_get($post,'meta_keyword') }}" placeholder="Enter meta keyword">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" class="form-control"
                                      placeholder="Enter meta description">{{ data_get($post,'meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a class="btn btn-danger" href="{{ route('admin.posts.index') }}">Cancel</a>
                @if(hasPermission('posts.edit'))
                    <button type="submit" class="btn btn-success">Update</button>
                @endif
            </div>
        </form>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">

@endsection
@section('script')
    <script src="{{asset('assets/plugins/bootstrap-select/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>
    <script>
        $(function () {
            $('#category_id').selectpicker({
                sanitize: false,
            });
            tinymce.init({
                selector: 'textarea.tinymce',
                min_height: 400,
                skin: 'small',
                icons: 'small',
                autoresize_min_height: 400,
                autoresize_bottom_margin: 5,
                document_base_url: '{{url('/')}}/',

                powerpaste_allow_local_images: true,
                powerpaste_word_import: 'clean',
                powerpaste_html_import: 'clean',

                removed_menuitems: 'newdocument',
                plugins: 'autoresize advcode powerpaste filemanager youtube responsivefilemanager searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern help ',
                toolbar: ['responsivefilemanager image media youtube | formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat'],

                fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt",
                extended_valid_elements: "svg[*]",
                relative_urls: true,
                remove_script_host: true,
                convert_urls: true,

                image_advtab: true,
                external_filemanager_path: "@external_filemanager_path()",
                external_plugins: {
                    "filemanager": "/vendor/responsivefilemanager/plugin.min.js"
                },
                filemanager_config: {
                    title: "File Manager",
                    access_key: "@filemanager_get_key()",
                    relative_url: 0,
                    can_delete: 0,
                    can_rename: 0
                },
                /*bootstrapConfig: {
                    url: "{{ asset('/assets/plugins/tinymce/plugins/bootstrap/')}}",
                    language: 'en',
                    elements: {"btn": true, "icon": true, "image": true, "table": true, "template": true, "breadcrumb": true, "pagination": true, "badge": true, "alert": true, "card": true, "snippet": true},
                    iconFont: 'fontawesome5',
                    imagesPath: "/{{ trim(str_replace('\\','/', (config('rfm.upload_dir'))),'/')}}",
                    //bootstrapCss: 'https://www.tinymce-bootstrap-plugin.com/assets/stylesheets/bootstrap.min.css',
                    editorStyleFormats: {
                        textStyles: true,
                        blockStyles: true,
                        containerStyles: true,
                        responsive: ['xs', 'sm', 'md', 'lg'], // xs sm md lg
                        spacing: ['all', 'x', 'y', 'top', 'right', 'bottom', 'left'] // all x y top right bottom left
                    }
                },*/
                table_default_attributes: {
                    border: '1',
                    class: 'table',
                },
                table_default_styles: {
                    width: '100%'
                },
                table_border_widths: [
                    {title: '1px', value: '1px'},
                    {title: '2px', value: '2px'},
                    {title: '3px', value: '3px'},
                    {title: '4px', value: '4px'},
                ],
                table_class_list: [
                    {title: 'None', value: ''},
                    {title: 'No Borders', value: 'table table-borderless'},
                    {title: 'Bordered', value: 'table table-bordered'}
                ],
                formats: {
                    alignleft: {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-left'},
                    aligncenter: {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-center'},
                    alignright: {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-right'},
                    alignjustify: {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-justify'},
                    bold: {inline: 'strong'},
                    italic: {inline: 'em'},
                    underline: {inline: 'u'},
                    sup: {inline: 'sup'},
                    sub: {inline: 'sub'},
                    strikethrough: {inline: 'del'}
                }
            });
        })
    </script>
@endsection
