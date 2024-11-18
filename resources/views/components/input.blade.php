@props(['label'=> '', 'name'=>'', 'type'=>'text', 'value'=>'', 'optional'=>false])

<div class="mb-3">
    <label for="{{$name}}" class="form-label">{{$label}}</label>
    <input type="{{$type}}" name="{{$name}}" id="{{$name}}" class="form-control" value="{{$value?: old($name)}}" {{$optional? '' : 'required'}}>
    @error($name)
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
