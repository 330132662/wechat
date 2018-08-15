@extends("layouts/app")
@section("title","模板列表")
@section("content")
    <a href="{{ url("templates/0/edit") }}">
        <button>创建新模板</button><p>
    </a>
    <div class="container-fluid">

        @for( $i = 0;$i<count($templates);$i++)
            @if($templates[$i]!=null)

                <li class="col-lg-3">
                    <img class="img" onclick="isSelect({{ $templates[$i] }})" src="{{ $templates[$i]->img }}"><br>
                    <span>{{ $templates[$i]->name }}</span>
                    @if(\Illuminate\Support\Facades\Auth::user()->id==1)
                        <button>删除</button>
                    @endif
                </li>
            @endif
        @endfor
    </div>

@endsection()
<style>
    .img {
        width: 200px;
        height: 300px;
    }
</style>
<script>
    function isSelect(tpl) {
        var x;
        var r = confirm("确定使用这个模板生成您的小程序？");
        if (r) {
            x = "正在生成";
            output(tpl);
        } else {
            x = "请继续浏览模板~";
        }
        // document.getElementById("")
    }

    function output(tpl) {
        // console.log(tpl);return ;

        axios.post('/weapps', {
            name: tpl['name'],
            img: tpl['img'],
        })
            .then(function (response) {
                console.log(response.status);
                if (response.status == 200) {
                    window.location = "/";
                }else{
                    alert("添加失败....");
                }
            })
            .catch(function (error) {
                console.log(error);
                alert("添加失败....");
            });


        /* axios({
             method: 'post',
             url: "/weapps",
             data: {
                 name: tpl['name'],
                 img: tpl['img'],
             }
         }).then(function (data) {
             console.log(data);
         });*/
    }
</script>