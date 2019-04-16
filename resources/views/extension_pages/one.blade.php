@extends('layouts.app')

@section('content')
    @php($flag = ($extension->serverless == "true") ? true : false)
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__("Ana Sayfa")}}</a></li>
            <li class="breadcrumb-item" aria-current="page"><a
                        href="{{route('extensions_settings')}}">{{__("Eklenti Yönetimi")}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $extension->name }}</li>
        </ol>
    </nav>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">{{__("Genel Ayarlar")}}</a></li>
            <li id="server_type"><a href="#tab_2" data-toggle="tab"
                                    aria-expanded="false">{{__("Eklenti Veritabanı")}}</a></li>
            <li id="server_type"><a href="#tab_2_2" data-toggle="tab" aria-expanded="false">{{__("Sayfa Ayarları")}}</a>
            </li>
            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">{{__("Widgetlar")}}</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <button class="btn btn-success btn-sm" onclick="updateExtension('general')">{{__("Kaydet")}}</button>
                <h3>{{__("Eklenti Adı")}}</h3>
                <input id="extensionName" type="text" name="name" class="form-control" value="{{$extension->name}}">
                <h3>{{__("Yayınlayan")}}</h3>
                <input type="text" name="name" class="form-control" value="{{$extension->publisher}}" disabled>
                <h3>{{__("Destek Email'i")}}</h3>
                <input id="support" type="text" name="email" class="form-control" value="{{$extension->support}}">
                <h3>{{__("Logo (Font Awesome Ikon)")}}</h3>
                <input id="icon" type="text" name="icon" class="form-control" value="{{$extension->icon}}">
                <h3>{{__("Versiyon")}}</h3>
                <input id="version" type="text" name="version" class="form-control" value="{{$extension->version}}">
                <h3>{{__("Servis")}}</h3>
                <input id="service" type="text" name="service" class="form-control" value="{{$extension->service}}">
                <h3>{{__("Eklenti için sunucuda betik çalıştırılması gerekiyor mu?")}}</h3>
                <div class="bd-example">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="serverless"
                               value="true" @if($flag)checked @endif>
                        <label class="form-check-label" for="inlineRadio1">{{__("Evet")}}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="serverless"
                               value="false" @if(!$flag)checked @endif>
                        <label class="form-check-label" for="inlineRadio2">{{__("Hayır")}}</label>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
                @include('l.modal-button',[
                    "class" => "btn-success btn-sm",
                    "target_id" => "add_database",
                    "text" => "Veri Ekle"
                ])<br><br>
                @include('l.table',[
                    "value" => $extension->database,
                    "title" => [
                        "Adı" , "Türü" , "Variable Adı", "" , "", ""
                    ],
                    "display" => [
                        "name" , "type", "variable", "variable:variable_old", "type:type_old", "name:name_old"
                    ],
                    "menu" => [
                        "Ayarları Düzenle" => [
                            "target" => "edit_database",
                            "icon" => "fa-edit"
                        ],
                        "Sil" => [
                            "target" => "remove_database",
                            "icon" => "fa-trash"
                        ]
                    ]
                ])

                @include('l.modal',[
                    "id"=>"add_database",
                    "title" => "Veri Ekle",
                    "url" => route('extension_settings_add'),
                    "next" => "reload",
                    "inputs" => [
                        "Sayfa Adı" => "name:text",
                        "Türü" => "type:text",
                        "Variable Adı" => "variable:text",
                        "table:database" => "table:hidden",
                        "-:" . extension()->_id => "extension_id:hidden"
                    ],
                    "submit_text" => "Veri Ekle"
                ])

                @include('l.modal',[
                    "id"=>"edit_database",
                    "title" => "Veri Düzenle",
                    "url" => route('extension_settings_update'),
                    "next" => "updateTable",
                    "inputs" => [
                        "Adı" => "name:text",
                        "Türü" => "type:text",
                        "Variable Adı" => "variable:text",
                        "Sayfa Adı:a" => "name_old:hidden",
                        "Türü:a" => "type_old:hidden",
                        "Variable Adı:a" => "variable_old:hidden",
                        "table:database" => "table:hidden",
                        "-:" . extension()->_id => "extension_id:hidden"
                    ],
                    "submit_text" => "Veri Düzenle"
                ])
                @include('l.modal',[
                    "id"=>"remove_database",
                    "title" => "Veri'yi Sil",
                    "url" => route('extension_settings_remove'),
                    "next" => "reload",
                    "text" => "Veri'yi silmek istediğinize emin misiniz? Bu işlem geri alınamayacaktır.",
                    "inputs" => [
                        "Sayfa Adı:a" => "name:hidden",
                        "Türü:a" => "type:hidden",
                        "Variable Adı:a" => "variable:hidden",
                        "table:database" => "table:hidden",
                        "-:" . extension()->_id => "extension_id:hidden"
                    ],
                    "submit_text" => "Veri'yi Sil"
                ])
            </div>
            <div class="tab-pane" id="tab_2_2">
                @include('l.modal-button',[
                    "class" => "btn-success btn-sm",
                    "target_id" => "add_view",
                    "text" => "Sayfa Ekle"
                ])<br><br>
                @include('l.table',[
                    "value" => $extension->views,
                    "title" => [
                        "Sayfa Adı" , "Çalışacak Betik/Fonksiyon" , "", ""
                    ],
                    "display" => [
                        "name" , "scripts", "name:name_old", "scripts:scripts_old"
                    ],
                    "menu" => [
                        "Ayarları Düzenle" => [
                            "target" => "edit_view",
                            "icon" => "fa-edit"
                        ],
                        "Kodu Düzenle" => [
                            "target" => "editPage",
                            "icon" => "fa-edit"
                        ],
                        "Sil" => [
                            "target" => "remove_view",
                            "icon" => "fa-trash"
                        ]
                    ]
                ])
                @include('l.modal',[
                    "id"=>"add_view",
                    "title" => "Sayfa Ekle",
                    "url" => route('extension_settings_add'),
                    "next" => "reload",
                    "inputs" => [
                        "Sayfa Adı" => "name:text",
                        "table:views" => "table:hidden",
                        "Çalışacak Betik/Fonksiyon" => "scripts:text",
                        "-:" . extension()->_id => "extension_id:hidden"
                    ],
                    "submit_text" => "Sayfa Ekle"
                ])
                @include('l.modal',[
                    "id"=>"edit_view",
                    "title" => "Sayfa Düzenle",
                    "url" => route('extension_settings_update'),
                    "next" => "updateTable",
                    "inputs" => [
                        "Sayfa Adı" => "name:text",
                        "Çalışacak Betik/Fonksiyon" => "d-scripts:text",
                        "Sayfa Adı:a" => "name_old:hidden",
                        "table:views" => "table:hidden",
                        "Çalışacak Betik/Fonksiyon:a" => "scripts_old:hidden",
                        "-:" . extension()->_id => "extension_id:hidden"
                    ],
                    "submit_text" => "Sayfa Düzenle"
                ])
                @include('l.modal',[
                    "id"=>"remove_view",
                    "title" => "Sayfa'yı Sil",
                    "url" => route('extension_settings_remove'),
                    "next" => "reload",
                    "text" => "Sayfa'yı silmek istediğinize emin misiniz? Bu işlem geri alınamayacaktır.",
                    "inputs" => [
                        "Sayfa Adı:a" => "name:hidden",
                        "table:views" => "table:hidden",
                        "Çalışacak Betik/Fonksiyon:a" => "scripts:hidden",
                        "-:" . extension()->_id => "extension_id:hidden"
                    ],
                    "submit_text" => "Sayfa'yı Sil"
                ])
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
                @include('l.modal-button',[
                    "class" => "btn-success btn-sm",
                    "target_id" => "add_widget",
                    "text" => "Widget Ekle"
                ])<br><br>
                @include('l.table',[
                    "value" => $extension->widgets,
                    "title" => [
                        "Widget Adı" , "Türü" , "Çalışacak Betik/Fonksiyon" , "", "", ""
                    ],
                    "display" => [
                        "name" , "type", "target", "name:name_old", "type:type_old", "target:target_old"
                    ],
                    "menu" => [
                        "Düzenle" => [
                            "target" => "edit_widget",
                            "icon" => "fa-edit"
                        ],
                        "Sil" => [
                            "target" => "remove_widget",
                            "icon" => "fa-trash"
                        ]
                    ]
                ])
                @include('l.modal',[
                    "id"=>"add_widget",
                    "title" => "Widget Ekle",
                    "url" => route('extension_settings_add'),
                    "next" => "reload",
                    "inputs" => [
                        "Widget Adı" => "name:text",
                        "Türü" => "type:text",
                        "table:widgets" => "table:hidden",
                        "Çalışacak Betik/Fonksiyon" => "target:text",
                        "-:" . extension()->_id => "extension_id:hidden"
                    ],
                    "submit_text" => "Widget Ekle"
                ])
                @include('l.modal',[
                    "id"=>"edit_widget",
                    "title" => "Widget Düzenle",
                    "url" => route('extension_settings_update'),
                    "next" => "updateTable",
                    "inputs" => [
                        "Widget Adı" => "name:text",
                        "Türü" => "type:text",
                        "Çalışacak Betik/Fonksiyon" => "target:text",
                        "Widget Adı:a" => "name_old:hidden",
                        "table:widgets" => "table:hidden",
                        "Türü:a" => "type_old:hidden",
                        "Çalışacak Betik/Fonksiyon:a" => "target_old:hidden",
                        "-:" . extension()->_id => "extension_id:hidden"
                    ],
                    "submit_text" => "Widget Düzenle"
                ])
                @include('l.modal',[
                    "id"=>"remove_widget",
                    "title" => "Widget'ı Sil",
                    "url" => route('extension_settings_remove'),
                    "next" => "reload",
                    "text" => "Widget'ı silmek istediğinize emin misiniz? Bu işlem geri alınamayacaktır.",
                    "inputs" => [
                        "Widget Adı:a" => "name:hidden",
                        "Türü:a" => "type:hidden",
                        "table:widgets" => "table:hidden",
                        "Çalışacak Betik/Fonksiyon:a" => "target:hidden",
                        "-:" . extension()->_id => "extension_id:hidden"
                    ],
                    "submit_text" => "Widget'ı Sil"
                ])
            </div>
            <!-- /.tab-pane -->
        </div>
    </div>
    <script>
        function editPage(element){
            let page = $(element).find("#name").html();
            window.location.href = window.location.href + '/' + page;
        }

        function updateExtension(type,tableId = null){
            Swal.fire({
                position: 'center',
                type: 'info',
                title: '{{__("Kaydediliyor...")}}',
                showConfirmButton: false,
            });
            let data = new FormData();
            data.append('extension_id','{{extension()->_id}}');
            data.append('type',type);
            data.append('name',$("#extensionName").val());
            data.append('icon',$("#icon").val());
            data.append('support',$("#support").val());
            data.append('version',$("#version").val());
            data.append('service',$("#service").val());
            data.append('serverless',$("input[name=serverless]:checked").val());

            request('{{route('extension_settings_update')}}',data,function(){
                Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: "{{__("Başarıyla kaydedildi")}}",
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function(){
                    location.reload();
                },1500);
            });
        }
    </script>
@endsection