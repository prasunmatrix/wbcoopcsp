@if(isset($showData))
    @if($showData != Null || $showData != '')
        @foreach ($showData as $item)
    <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
      
        <title>{{ $item->title }}</title>
    
        <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/blog/">
    
        <!-- Bootstrap core CSS -->
        <link href="{{ asset('css/admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    
        <!-- Custom styles for this template -->
        <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
        <link href="blog.css" rel="stylesheet">
      </head>
    
      <body>
       
    
        <main role="main" class="container">
          <div class="row">
            <div class="col-md-8 blog-main">
              @if(isset($item->image))
                    @if($item->image != NULL && $item->image !='')
                        @if(file_exists(public_path('uploads/cms/thumbs/'.$item->image)))
                            @php $imgPath = URL:: asset('uploads/cms/thumbs').'/'.$item->image;@endphp
                            <img class="img-bordered-sm" src="{{$imgPath}}" alt="user image" width="100%" height="50%">
                        @else
                            @php $imgPath = '';@endphp    
                        @endif
                    @else
                        @php $imgPath = '';@endphp 
                    @endif
                @endif
              <h3 class="pb-3 mb-4 font-italic border-bottom">
                {{ $item->title }}
              </h3>
              <div class="blog-post">
                {!! $item->description !!}
              </div><!-- /.blog-post -->
    
            </div><!-- /.blog-main -->
            
          </div><!-- /.row -->
    
        </main><!-- /.container -->
    
      </body>
    </html>
        @endforeach
    @endif
@endif
