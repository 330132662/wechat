@extends('layouts/app')

@section("title","文章发布")
@section("content")
    <div class="row">
        <div class="col-xs-10" style="margin-left: -300px; width: 100%;">
        @include('UEditor::head')
        <!-- 加载编辑器的容器 -->
            <input placeholder="文章标题" name="name" id="name" style="width:700px"/>
            <script id="container" name="content" type="text/plain">

            </script>
            <button onclick="saveArticle()">保存</button>
        </div>
        <div class="col-lg-2">
            <h3>额外设置项</h3>
            <span>发布到小程序：</span>
            <select name="weid" id="weid" title="我发布过的小程序">
                @if($apps!=null)
                    <option value="0">请选择！</option>
                    @foreach($apps as $app)
                        <option value="{{ $app->id }}">{{ $app->apptitle }}</option>
                    @endforeach
                @endif
            </select> <p></p>
            <span>文章类型</span>
            <label>
                <select id="type">
                    <option value="0">请选择</option>
                    <option value="1">文章</option>
                    <option value="2">服务</option>
                    <option value="3" hidden>产品</option>
                </select>
            </label>


        </div>
    </div>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        const ue = UE.getEditor('container', {
            autoheight: false
        });
        ue.ready(function () {
            ue.setHeight(600);
            //设置编辑器的内容
            // ue.setContent('hello');
            // //获取html内容，返回: <p>hello</p>
            // var html = ue.getContent();
            // //获取纯文本内容，返回: hello
            // var txt = ue.getContentTxt();
        }) ;

        function saveArticle() {
            const weid = document.getElementById("weid").value;
            const content = ue.getContent();
            const name = document.getElementById("name").value;
            const type = document.getElementById("type").value;
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
                url: "/articles",
                data: {
                    weid: weid,
                    name: name,
                    content: content,
                    type : type
                }
            }).then(function (data) {
                console.log(data);
                alert(data.data);
                if (data.status == 200) {
                    window.location = '/articles';
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