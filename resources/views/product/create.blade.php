@extends('layouts/app')

@section("title","产品发布")
@section("content")
    <div class="row">
        <div class="col-xs-10" style="margin-left: -300px; width: 100%;">
        @include('UEditor::head')
        <!-- 加载编辑器的容器 -->
            @if($product == null)

                商品名称：<input placeholder="商品名称" name="name" id="name" style="width:700px"/><br>
                价格：<input placeholder="修改价格" id="price" type="number">
                <script id="container" name="desc" type="text/plain">
                </script>
            @else
                商品名称：<input placeholder="商品名称" value="{{ $product->name }}" name="name" id="name" style="width:700px"/>
                <br>
                价格：<input placeholder="修改价格" id="price" type="number" value="{{ $product->price }}">
                <script id="container" name="desc" type="text/plain">
                </script>
            @endif
            <input name="id" hidden value="{{ $product->id }}">
            <button onclick="saveArticle()">保存</button>
        </div>
        <div class="col-lg-2">
            <p>
            <h3>额外设置项</h3></p>
            <span>发布到小程序：</span>
            <select name="weid" id="weid" title="我发布过的小程序">
                @if($apps!=null)
                    <option value="0">请选择！</option>
                    @foreach($apps as $app)
                        <option value="{{ $app->id }}">{{ $app->name }}</option>
                    @endforeach
                @endif

            </select>

        </div>
    </div>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container', {
            autoheight: false
        });
        ue.ready(function () {
            ue.setHeight(600);
            //设置编辑器的内容
            ue.setContent('{{ $product->desc }}');
            // //获取html内容，返回: <p>hello</p>
            // var html = ue.getContent();
            // //获取纯文本内容，返回: hello
            // var txt = ue.getContentTxt();
        })

        function saveArticle() {
            const weid = document.getElementById("weid").value;
            const content = ue.getContent();
            const name = document.getElementById("name").value;
            const price = document.getElementById("price").value;
            if (content === '') {
                alert("请填写内容！");
                return;
            }
            if (name === '') {
                alert("请填写标题！");
                return;
            }
            if (weid == 0) {
                alert("请选择小程序！");
                return;
            }
            // console.log(weid);
            axios({
                method: "post",
                url: "/products",
                data: {
                    weid: weid,
                    name: name,
                    desc: content,
                    price: price
                }
            }).then(function (data) {
                console.log(data);
                alert(data.data);
                if (data.status == 200) {
                    window.location = '/products';
                }

            })
                .catch(function (err) {
                    console.log(err);
                });
        }
    </script>
    <style>
        #container {
            margin-right: 200px;
        }

    </style>
@endsection