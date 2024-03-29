@extends('layouts.app')

@section('content')

    <x-header title="New Article" />

    <div class="container bg-white py-2">
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('message') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <form action="{{route('postArticle')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="Title">Title</label>
                <input type="text" name="title" class="form-control" value="{{old('title')}}" id="title">
                @error('title')<small class="text-danger">{{$message}}</small>@enderror
            </div>

            <div class="form-group">
                <label for="lead">Lead Image</label>
                <input type="file" name="image" class="form-control" id="lead">
                @error('image')<small class="text-danger">{{$message}}</small>@enderror
            </div>
        

            @error('body')<small class="text-danger">{{$message}}</small>@enderror
            <textarea id="editor"  name="body">
                {{old('body')}}
            </textarea>
            <br>
            <button type="submit" class="btn btn-primary btn-lg mb-3">Publish</button>
        </form>
    </div>


<script src="https://cdn.tiny.cloud/1/d3utf658spf5n1oft4rjl6x85g568jj7ourhvo2uhs578jt9/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>

    tinymce.init({
      height : "600",
      selector: '#editor',
      plugins: 'template autoresize autolink image fullscreen imagetools emoticons link lists hr paste media table',
      toolbar: 'insert undo redo fullscreen fontsizeselect alignleft aligncenter alignright alignjustify h1 h2 bold italic numlist bullist image link emoticons hr paste table',
      contextmenu: "link image table paste",
      image_class_list: [
    {title: 'img-responsive', value: 'img-responsive'},
],
      relative_urls: false,
      document_base_url:"{{url('')}}",
      content_style: 'textarea { padding: 20px; }',
      templates: [
    {title: 'Some title 1', description: 'Some desc 1', content: 'My content'},
    {title: 'Some title 2', description: 'Some desc 2', url: 'development.html'},
  ],
      autoresize_bottom_margin: 50,
      images_upload_handler: function (blobInfo, success, failure) {
           var xhr, formData;
           xhr = new XMLHttpRequest();
           xhr.withCredentials = false;
           xhr.open('POST', '{{ route("image.upload") }}');
           var token = '{{ csrf_token() }}';
           xhr.setRequestHeader("X-CSRF-Token", token);
           xhr.onload = function() {
               var json;
               if (xhr.status != 200) {
                   failure('HTTP Error: ' + xhr.status);
                   return;
               }
               json = JSON.parse(xhr.responseText);
               if (!json || typeof json.location != 'string') {
                   failure('Invalid JSON: ' + xhr.responseText);
                   return;
               }
                var image = $("#images").val()
               image += (json.location);
               image += "~";
               $('#images').val(image)
               success(json.location);
           };
           formData = new FormData();
           formData.append('file', blobInfo.blob(), blobInfo.filename());
           xhr.send(formData);
       }
    });
 
 
</script>

    


@endsection