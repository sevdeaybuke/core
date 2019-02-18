@if(!isset($title) && !isset($value) && !isset($display))
    @php(__("Tablo Oluşturulamadı."))
@else
    @php($rand = str_random(10))
    <table class="table hover" id="{{$rand}}">
        <thead>
        <tr>
            @foreach($title as $i)
                @if($i == "*hidden*")
                    <th scope="col" hidden>{{ __($i) }}</th>
                @else
                    <th scope="col">{{ __($i) }}</th>
                @endif
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach ($value as $k)
            <tr id="{{str_random(10)}}" @isset($onclick)onclick="{{$onclick}}(this)" @endisset>
                @foreach($display as $item)
                    @if($item == "server_id" || $item == "extension_id" || $item == "script_id")
                        @if(is_array($k))
                            <td id="{{$item}}" hidden>{{$k[$item]}}</td>
                        @else
                            <td id="{{$item}}" hidden>{{$k->__get($item)}}</td>
                        @endif

                    @elseif(count(explode(':',$item)) > 1)
                        @if(is_array($k))
                            <td id="{{explode(':',$item)[1]}}" hidden>{{$k[explode(':',$item)[0]]}}</td>
                        @else
                            <td id="{{explode(':',$item)[1]}}" hidden>{{$k->__get(explode(':',$item)[0])}}</td>
                        @endif
                    @else
                        @if(is_array($k))
                            <td id="{{$item}}">{{$k[$item]}}</td>
                        @else
                            <td id="{{$item}}">{{$k->__get($item)}}</td>
                        @endif
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(isset($menu))
        <script>
            @isset($setCurrentVariable)
            var {{$setCurrentVariable}};
            @endisset
            $.contextMenu({
                selector: '#{{$rand}} tr',
                callback: function (key, options) {
                    @isset($setCurrentVariable)
                    {{$setCurrentVariable}} = options.$trigger[0].getAttribute("id");
                    @endisset
                    let target = $("#" + key);
                    inputs =[];
                    $("#" + key + " input , #" + key + ' select').each(function (index, value) {
                        let element_value = $("#" + options.$trigger[0].getAttribute("id") + " #" + value.getAttribute('name')).html();
                        if(element_value){
                            inputs.push($("#" + options.$trigger[0].getAttribute("id") + " #" + value.getAttribute('name')));
                            $("#" + key + " select[name='" + value.getAttribute('name') + "']" + " , "
                                + "#" + key + " input[name='" + value.getAttribute('name') + "']").val(element_value);
                        }
                    });
                    target.modal('show');
                },
                items: {
                    @foreach($menu as $name=>$config)
                        "{{$config['target']}}" : {name: "{{$name}}" , icon: "{{$config['icon']}}"},
                    @endforeach
                }
            });
        </script>
    @endif
@endif