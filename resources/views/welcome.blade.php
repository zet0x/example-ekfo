@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        @auth
        <div class="col-xs-12 col-lg-12">
            <div class="user-name">{{ trans('welcome.hello') }} <span>{{ Auth::user()->name }}</span></div>
        </div>
        @endauth  
        <div class="clearfix"></div>     
        <div class="col-xs-12 col-lg-12">
            @if(count($arResult['posts']) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('welcome.problem') }}</th>
                        <th>{{ trans('welcome.decision') }}</th>
                        <th>{{ trans('welcome.rank') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arResult['posts'] as $post)
                        <tr>
                            <td>{{ $post->problem }}</td>
                            <td>{{ $post->decision }}</td>
                            <td>
                                <div id="elem-{{ $post->id }}" data-rating="{{ $post->rank }}"></div> 
                                 @auth
                                    @if($arResult['role']->id == 2)
                                    <script type="text/javascript">
                                        $('#elem-{{ $post->id }}').raty({
                                            score: function() {
                                                return $(this).attr('data-rating');
                                              },
                                            click: function(score) {
                                                $.ajax({
                                                  url: '/rank',
                                                  data: { elem: $(this).attr('id'),rank:score },
                                                  success: function(data) {
                                                    
                                                  }
                                                });
                                              }
                                        });
                                    </script>
                                    @else
                                    <script type="text/javascript">
                                    
                                        $('#elem-{{ $post->id }}').raty({
                                            score: function() {
                                                return $(this).attr('data-rating');
                                              },
                                            readOnly : true
                                        });
                                    </script>
                                    @endif
                                 @else
                                 <script type="text/javascript">
                                    
                                    $('#elem-{{ $post->id }}').raty({
                                        score: function() {
                                            return $(this).attr('data-rating');
                                          },
                                        readOnly : true
                                    });
                                </script>
                                 @endauth

                                 
                            </td>
                        </tr>    
                    @endforeach
                </tbody>
                
            </table>
            @else
            
              <h2>{{ trans('welcome.none') }}</h2>  
            
            @endif
            

        </div>
    </div>
</div>
@auth
    @if($arResult['role']->id == 1)
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <h3 class="text-center">{{ trans('welcome.add') }}</h3>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <form method="post">
                    @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{ trans('welcome.problem') }}</label>
                            <textarea class="form-control" placeholder="{{ trans('welcome.problem') }}" name="problem" required></textarea>
                         </div>
                         <div class="form-group">
                            <label for="exampleInputEmail1">{{ trans('welcome.decision') }}</label>
                            <textarea class="form-control" placeholder="{{ trans('welcome.decision') }}" name="decision" required></textarea>
                         </div>    
                         <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">{{ trans('welcome.send') }}</button>
                         </div>
                    </form>
                    
                </div>
            </div>
        </div>
    @endif
@endauth
@endsection
