<div class="form-group {{$col ?? ''}} {{$required ?? ''}}" >
    <label for="{{$name}}">{{$labelName}}</label>
    <textarea name="{{$name}}" id="{{$name}}" class="form-control {{$class ?? ''}}"
     placeholder="{{$placeholder ?? ''}}" @if($readonly) readonly @endif>{{ $value ?? '' }}</textarea>
</div>
