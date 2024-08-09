@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            EDIT: {!! $news->title !!}

            <form method="post" action="{{ route('news.destroy', $news) }}" class="float-right">
                @csrf
                @method('DELETE')
                <button type="submit" class="badge badge-danger">DELETE</button>
            </form>
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('news.update', $news) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="title">Title (internal usage) *</label>
                        <input type="text" class="form-control" id="title" name="title"
                               value="{{ old('title', $news->title) }}"/>
                    </div>
                </div>

                <hr>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="active_from">Active from *</label>
                        <input type="datetime-local" class="form-control" id="active_from" name="active_from"
                               value="{{ old('active_from', $news->active_from) }}"/>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="active_to">Active to *</label>
                        <input type="datetime-local" class="form-control" id="active_to" name="active_to"
                               value="{{ old('active_to', $news->active_to) }}"/>
                    </div>
                    <div class="form-group col-md-2">
                        <div class="custom-control custom-checkbox mg-t-35">
                            <input type="checkbox" class="custom-control-input"
                                   @if($news->active) checked @endif
                                   id="active" name="active" value="1">
                            <label class="custom-control-label" for="active">
                                Is Active?
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-md-1"></div>
                    <div class="form-group col-md-2">
                        <label for="side">SIDE *</label> <br>
                        <select name="side" id="side" style="width: 100%">
                            <option value="">-- SELECT --</option>
                            <option value="1" @if(old('side', $news->side) == 1) selected @endif>RIGHT</option>
                            <option value="2" @if(old('side', $news->side) == 2) selected @endif>LEFT</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="icon_id">ICON</label>
                        <select name="icon_id" id="icon_id" style="width: 100%">
                            <option value="">-- SELECT --</option>
                            @foreach($icons as $icon)
                                <option value="{!! $icon->id !!}"
                                        @if(old('icon_id', $news->icon_id) == $icon->id) selected @endif>
                                    {!! $icon->name !!}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="heading">Heading</label>
                        <input type="text" class="form-control" id="heading" name="heading"
                               value="{{ old('title', $news->heading) }}"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="content">Content *</label>

                        <textarea name="content" id="content" cols="30" class="form-control"
                                  rows="10">{!! old('content', $news->content) !!}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="heading">Heading image</label> <br>
                        <input type="file" autocomplete="off" name="file"><br>
                        <img src="{{ $news->getFirstMediaUrl('extra-content') }}" alt="" width="150">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">UPDATE</button>
            </form>

        </div>
    </div>
@endsection
