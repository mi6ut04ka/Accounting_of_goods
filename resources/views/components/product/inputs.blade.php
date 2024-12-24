@props(['price'=>0, 'cost'=>'0', 'in_stock'=>0, 'url'=>'', 'description'=>''])
<x-input name="price" value="{{$price}}" label="Цена" type="number"/>
<x-input name="cost" value="{{$cost}}" label="Себестоимость" type="number"/>
<x-input name="in_stock" value="{{$in_stock}}" label="Количество" type="number"/>
<x-input name="description" value="{{$description}}" label="Описание" type="text"/>
<x-photo-input :url="$url?? ''"/>
